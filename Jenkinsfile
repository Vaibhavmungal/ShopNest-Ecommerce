// ============================================================
//  Jenkinsfile — ShopNest E-Commerce
//  Declarative Pipeline: Lint → Build → Push → Deploy to Kubernetes
//
//  REQUIREMENTS on Jenkins server:
//    - Docker + kubectl installed (Jenkins user in "docker" group)
//    - Docker Hub credentials stored as: Docker (username & password)
//    - Kubernetes cluster access configured (~/.kube/config)
// ============================================================

pipeline {

    agent any

    // ── ENVIRONMENT VARIABLES ─────────────────────────────────
    environment {
        DOCKER_USER   = "vaibhavvv85"
        IMAGE_NAME    = "shopnest-app"
        IMAGE_TAG     = "${BUILD_NUMBER}"
        K8S_DIR       = "k8s"
        K8S_NAMESPACE = "shopnest"
    }

    // ── BUILD OPTIONS ─────────────────────────────────────────
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timeout(time: 30, unit: 'MINUTES')
        disableConcurrentBuilds()
    }

    // ── TRIGGERS ─────────────────────────────────────────────
    triggers {
        githubPush()
    }

    stages {

        // ── STAGE 1: CHECKOUT ─────────────────────────────────
        stage('Stage 1: Checkout') {
            steps {
                echo "📥 Checking out repository..."
                checkout scm
            }
        }

        // ── STAGE 2: PHP LINT ─────────────────────────────────
        stage('Stage 2: PHP Lint') {
            steps {
                echo "🔍 Running PHP syntax check..."
                sh '''#!/bin/bash
                    which php || { echo "❌ PHP not found on agent. Install PHP 8.2."; exit 1; }
                    php --version

                    ERROR=0
                    while IFS= read -r -d "" file; do
                        php -l "$file" || ERROR=1
                    done < <(find . -name "*.php" \
                        ! -path "./vendor/*" \
                        ! -path "./uploads/*" \
                        ! -path "./logs/*" \
                        -print0)

                    if [ $ERROR -ne 0 ]; then
                        echo "❌ PHP syntax errors found! Fix them before deploying."
                        exit 1
                    fi
                    echo "✅ All PHP files passed syntax check."
                '''
            }
        }

        // ── STAGE 3: BUILD DOCKER IMAGE ───────────────────────
        stage('Stage 3: Build Docker Image') {
            steps {
                echo "🐳 Building Docker image: ${DOCKER_USER}/${IMAGE_NAME}:${IMAGE_TAG}..."
                sh """
                    docker build \
                        --tag ${DOCKER_USER}/${IMAGE_NAME}:${IMAGE_TAG} \
                        --tag ${DOCKER_USER}/${IMAGE_NAME}:latest \
                        --file Dockerfile \
                        .
                    echo "✅ Docker image built successfully."
                """
            }
        }

        // ── STAGE 4: PUSH TO DOCKER HUB ───────────────────────
        stage('Stage 4: Push to Docker Hub') {
            steps {
                echo "🐳 Pushing Docker image to Docker Hub..."
                withCredentials([usernamePassword(credentialsId: 'Docker', 
                                                 usernameVariable: 'DOCKER_HUB_USER', 
                                                 passwordVariable: 'DOCKER_HUB_PASS')]) {
                    sh """
                        echo "\$DOCKER_HUB_PASS" | docker login -u "\$DOCKER_HUB_USER" --password-stdin
                        docker push ${DOCKER_USER}/${IMAGE_NAME}:${IMAGE_TAG}
                        docker push ${DOCKER_USER}/${IMAGE_NAME}:latest
                        docker logout
                        echo "✅ Docker image pushed successfully!"
                    """
                }
            }
        }

        // ── STAGE 5: DEPLOY TO KUBERNETES ─────────────────────
        stage('Stage 5: Deploy to Kubernetes') {
            steps {
                echo "🚀 Deploying to Kubernetes cluster..."
                sh """
                    # Apply Kubernetes manifest
                    kubectl apply -f ${K8S_DIR}/deployment.yaml

                    # Update deployment to use the newly built image tag
                    kubectl set image deployment/ecommerce-app web=${DOCKER_USER}/${IMAGE_NAME}:${IMAGE_TAG}

                    echo "✅ Manifests applied and image updated."
                """
            }
        }

        // ── STAGE 6: HEALTH CHECK & ROLLOUT ───────────────────
        stage('Stage 6: Health Check') {
            steps {
                echo "🏥 Verifying deployment rollout..."
                sh """
                    # Wait for app deployment rollout to complete successfully
                    kubectl rollout status deployment/ecommerce-app --timeout=120s

                    echo "✅ Deployment is healthy and running!"
                    kubectl get pods,svc -n ${K8S_NAMESPACE} -o wide
                """
            }
        }

        // ── STAGE 7: CLEANUP ──────────────────────────────────
        stage('Stage 7: Cleanup') {
            steps {
                echo "🧹 Cleaning up dangling Docker images..."
                sh 'docker image prune -f || true'
            }
        }
    }

    // ── POST ACTIONS ──────────────────────────────────────────
    post {

        success {
            echo """
            ╔══════════════════════════════════════════╗
            ║  ✅  BUILD #${BUILD_NUMBER} SUCCEEDED      ║
            ║  ShopNest deployed to Kubernetes!        ║
            ║  Store URL: http://<node-ip>:30082       ║
            ╚══════════════════════════════════════════╝
            """
        }

        failure {
            echo """
            ╔══════════════════════════════════════════╗
            ║  ❌  BUILD #${BUILD_NUMBER} FAILED         ║
            ║  Check the console output above.         ║
            ╚══════════════════════════════════════════╝
            """
            sh """
                kubectl describe pods -l app=ecommerce-web || true
                kubectl logs -l app=ecommerce-web --tail=50 || true
            """
        }

        always {
            echo "🔚 Pipeline finished."
        }
    }
}
