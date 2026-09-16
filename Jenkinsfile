pipeline {
    agent any

    environment {
        IMAGE_NAME = "aws-ecommerce"
        IMAGE_TAG  = "${BUILD_NUMBER}"
        K8S_FILE   = "k8s/deployment.yaml"
        EKS_CLUSTER = "shoping"
        AWS_REGION  = "us-east-1"
    }

    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timeout(time: 15, unit: 'MINUTES')
        disableConcurrentBuilds()
    }

    triggers {
        githubPush()
    }

    stages {
        stage('PHP Lint') {
            steps {
                sh '''
                    find . -name "*.php" ! -path "./vendor/*" ! -path "./uploads/*" -exec php -l {} +
                '''
            }
        }

        stage('Build Docker Image') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'Docker', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    sh """
                        docker build -t \$DOCKER_USER/${IMAGE_NAME}:${IMAGE_TAG} -t \$DOCKER_USER/${IMAGE_NAME}:latest .
                    """
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'Docker', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    sh """
                        echo "\$DOCKER_PASS" | docker login -u "\$DOCKER_USER" --password-stdin
                        docker push \$DOCKER_USER/${IMAGE_NAME}:${IMAGE_TAG}
                        docker push \$DOCKER_USER/${IMAGE_NAME}:latest
                        docker logout
                    """
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                withCredentials([
                    usernamePassword(credentialsId: 'Docker', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS'),
                    usernamePassword(credentialsId: 'aws-credentials', usernameVariable: 'AWS_ACCESS_KEY_ID', passwordVariable: 'AWS_SECRET_ACCESS_KEY')
                ]) {
                    sh """
                        aws eks update-kubeconfig --region ${AWS_REGION} --name ${EKS_CLUSTER}
                        kubectl apply -f ${K8S_FILE}
                        kubectl set image deployment/ecommerce-app web=\$DOCKER_USER/${IMAGE_NAME}:${IMAGE_TAG}
                        kubectl rollout status deployment/ecommerce-app --timeout=120s
                    """
                }
            }
        }
    }

    post {
        failure {
            withCredentials([usernamePassword(credentialsId: 'aws-credentials', usernameVariable: 'AWS_ACCESS_KEY_ID', passwordVariable: 'AWS_SECRET_ACCESS_KEY')]) {
                sh """
                    aws eks update-kubeconfig --region ${AWS_REGION} --name ${EKS_CLUSTER} || true
                    kubectl describe pods -l app=ecommerce-web || true
                    kubectl logs -l app=ecommerce-web --tail=50 || true
                """
            }
        }
        always {
            sh 'docker image prune -f || true'
        }
    }
}
