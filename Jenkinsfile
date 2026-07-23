// ============================================================
//  Jenkinsfile — ShopNest E-Commerce
//  Declarative Pipeline: Lint → Build → Deploy via Docker Compose
//
//  REQUIREMENTS on Jenkins server:
//    - Docker + Docker Compose installed
//    - Jenkins user in the "docker" group
//    - GitHub credentials stored as: github-credentials
//    - .env file for production stored as:  shopnest-env-file  (Secret File)
//    - MYSQL_ROOT_PASSWORD stored as:       shopnest-db-root-pass (Secret Text)
// ============================================================

pipeline {

    // Run on any available Jenkins agent that has Docker installed
    agent any

    // ── ENVIRONMENT VARIABLES ─────────────────────────────────
    environment {
        IMAGE_NAME    = "shopnest-app"
        IMAGE_TAG     = "${BUILD_NUMBER}"          // e.g. shopnest-app:42
        CONTAINER_APP = "shopnest_app"
        CONTAINER_DB  = "shopnest_db"
        COMPOSE_FILE  = "docker-compose.yml"
        REPO_URL      = "https://github.com/Vaibhavmungal/ShopNest-Ecommerce.git"
        BRANCH        = "main"
    }

    // ── BUILD OPTIONS ─────────────────────────────────────────
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))   // keep last 10 builds
        timeout(time: 30, unit: 'MINUTES')               // fail if build hangs
        disableConcurrentBuilds()                        // one build at a time
        timestamps()                                     // add timestamps to logs
    }

    // ── TRIGGERS ─────────────────────────────────────────────
    // Polls GitHub every minute for new commits.
    // Alternatively, set up a GitHub webhook pointing to:
    //   http://<jenkins-server>:8080/github-webhook/
    triggers {
        pollSCM('* * * * *')
    }

    stages {

        // ── STAGE 1: CHECKOUT ─────────────────────────────────
        stage('Checkout') {
            steps {
                echo "📥 Checking out repository..."
                checkout scm
                echo "✅ Checkout complete. Build #${BUILD_NUMBER}"
            }
        }

        // ── STAGE 2: PHP SYNTAX LINT ──────────────────────────
        stage('PHP Lint') {
            steps {
                echo "🔍 Running PHP syntax check on all .php files..."
                sh '''
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
        stage('Build Docker Image') {
            steps {
                echo "🐳 Building Docker image: ${IMAGE_NAME}:${IMAGE_TAG}..."
                sh '''
                    docker build \
                        --tag ${IMAGE_NAME}:${IMAGE_TAG} \
                        --tag ${IMAGE_NAME}:latest \
                        --no-cache \
                        --file Dockerfile \
                        .
                    echo "✅ Docker image built: ${IMAGE_NAME}:${IMAGE_TAG}"
                    docker image ls | grep ${IMAGE_NAME}
                '''
            }
        }

        // ── STAGE 4: SETUP .ENV FILE ──────────────────────────
        // For testing, uses .env.example if secret credentials are not set
        stage('Setup Environment') {
            steps {
                echo "⚙️ Preparing .env configuration..."
                script {
                    try {
                        withCredentials([file(credentialsId: 'shopnest-env-file', variable: 'ENV_FILE')]) {
                            sh 'cp "$ENV_FILE" .env'
                            echo "✅ Using production .env from Jenkins credentials."
                        }
                    } catch (Exception e) {
                        echo "⚠️ No Jenkins secret credential found ('shopnest-env-file'). Falling back to .env.example for testing."
                        sh 'cp .env.example .env'
                        echo "✅ Created .env from .env.example."
                    }
                }
            }
        }

        // ── STAGE 5: DEPLOY WITH DOCKER COMPOSE ───────────────
        stage('Deploy') {
            steps {
                echo "🚀 Deploying ShopNest with Docker Compose..."
                sh '''
                    # Pull any updated base images (mysql, phpmyadmin)
                    docker compose -f ${COMPOSE_FILE} pull db phpmyadmin || true

                    # Update the app image tag in docker-compose for this build
                    # Rebuild & restart only the app service (zero-downtime swap)
                    docker compose -f ${COMPOSE_FILE} up -d --build --remove-orphans

                    echo "✅ Deployment complete."
                    docker compose -f ${COMPOSE_FILE} ps
                '''
            }
        }

        // ── STAGE 6: HEALTH CHECK ─────────────────────────────
        stage('Health Check') {
            steps {
                echo "🏥 Waiting for containers to become healthy..."
                sh '''
                    # Wait up to 90 seconds for the app container to be running
                    TIMEOUT=90
                    ELAPSED=0
                    until docker inspect --format="{{.State.Status}}" ${CONTAINER_APP} 2>/dev/null | grep -q "running"; do
                        sleep 5
                        ELAPSED=$((ELAPSED + 5))
                        if [ $ELAPSED -ge $TIMEOUT ]; then
                            echo "❌ Timed out waiting for ${CONTAINER_APP} to start."
                            docker compose -f ${COMPOSE_FILE} logs app
                            exit 1
                        fi
                        echo "⏳ Waiting for app container... (${ELAPSED}s)"
                    done

                    echo "✅ App container is running."
                    docker compose -f ${COMPOSE_FILE} ps
                    docker compose -f ${COMPOSE_FILE} logs --tail=20 app
                '''
            }
        }

        // ── STAGE 7: CLEANUP OLD IMAGES ───────────────────────
        stage('Cleanup') {
            steps {
                echo "🧹 Removing dangling Docker images to free disk space..."
                sh '''
                    docker image prune -f || true
                    echo "✅ Cleanup done."
                    docker image ls | grep ${IMAGE_NAME} || true
                '''
            }
        }
    }

    // ── POST ACTIONS ──────────────────────────────────────────
    post {

        success {
            echo """
            ╔══════════════════════════════════════════╗
            ║  ✅  BUILD #${BUILD_NUMBER} SUCCEEDED      ║
            ║  ShopNest deployed successfully!         ║
            ║  http://<your-server-ip>:8080            ║
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
            // Print last 50 lines of app logs to help debug
            sh 'docker compose -f ${COMPOSE_FILE} logs --tail=50 app || true'
        }

        always {
            echo "🔚 Pipeline finished. Cleaning up workspace files..."
            // Remove the .env file from the workspace after each run (security)
            sh 'rm -f .env || true'
        }
    }
}
