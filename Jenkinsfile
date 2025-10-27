pipeline {
  agent any
  environment {
    IMAGE_NAME = 'yustikanur/prediksi-cuaca'
    REGISTRY = 'https://index.docker.io/v1/'
    REGISTRY_CREDENTIALS = 'dockerhub-credentials'
  }
  stages {
    stage('Checkout') {
      steps { checkout scm }
    }
    stage('Install Dependencies') {
      steps {
        bat 'composer --version || echo "composer not found"'
        bat 'composer install --no-interaction --prefer-dist'
      }
    }
   stage('Run Tests') {
        steps {
            bat '''
            if exist phpunit.xml (
                vendor\\bin\\phpunit --testdox
            ) else (
                echo No tests
            )
            '''
        }
    }
    stage('Build Docker Image') {
      steps {
        bat """docker build -t ${env.IMAGE_NAME}:${env.BUILD_NUMBER} ."""
      }
    }
    stage('Push Docker Image') {
      steps {
        withCredentials([usernamePassword(credentialsId: env.REGISTRY_CREDENTIALS, usernameVariable: 'USER', passwordVariable: 'PASS')]) {
          bat """docker login -u %USER% -p %PASS%"""
          bat """docker push ${env.IMAGE_NAME}:${env.BUILD_NUMBER}"""
          bat """docker tag ${env.IMAGE_NAME}:${env.BUILD_NUMBER} ${env.IMAGE_NAME}:latest"""
          bat """docker push ${env.IMAGE_NAME}:latest"""
        }
      }
    }
    stage('Deploy (optional)') {
      steps {
        echo "Deploy step: jalankan docker-compose pull && docker-compose up -d pada server target"
        // contoh: ssh ke server deploy lalu lakukan pull dan restart container
      }
    }
  }
  post {
    always { echo "Pipeline selesai (build #${env.BUILD_NUMBER})" }
  }
}
