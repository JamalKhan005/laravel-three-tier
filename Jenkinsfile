pipeline {
    agent any
    triggers { pollSCM('H/2 * * * *') }
    environment {
        IMAGE = 'laravel-app'
        TAG   = "${BUILD_NUMBER}"
    }
    stages {
        stage('Checkout') {
            steps { checkout scm }
        }
        stage('Build Docker Image') {
            steps { sh 'docker build -t $IMAGE:$TAG .' }
        }
        stage('Load Image into Minikube') {
            steps { sh 'minikube image load $IMAGE:$TAG' }
        }
        stage('Deploy Database Tier') {
            steps {
                sh '''
                  kubectl apply -f k8s/configmap.yaml
                  kubectl apply -f k8s/mysql.yaml
                  kubectl rollout status deployment/mysql --timeout=240s
                '''
            }
        }
        stage('Deploy Laravel Tier') {
            steps {
                sh '''
                  sed "s|IMAGE_TAG|$TAG|g" k8s/laravel.yaml | kubectl apply -f -
                  kubectl rollout status deployment/laravel-app --timeout=240s
                '''
            }
        }
        stage('Run DB Migrations') {
            steps { sh 'kubectl exec deploy/laravel-app -- php artisan migrate --force' }
        }
        stage('Verify') {
            steps {
                sh '''
                  kubectl get pods,svc
                  curl -s http://$(minikube ip):30080/health
                '''
            }
        }
    }
}
