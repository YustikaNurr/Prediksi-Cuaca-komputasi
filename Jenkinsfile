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
        bat 'if [ -f phpunit.xml ]; then vendor/bin/phpunit --testdox || true; else echo "No tests"; fi'
      }
    }
    stage('Build Docker Image') {
      steps {
        script {
          docker.build("${IMAGE_NAME}:${env.BUILD_NUMBER}")
        }
      }
    }
    stage('Push Docker Image') {
      steps {
        script {
          docker.withRegistry(REGISTRY, REGISTRY_CREDENTIALS) {
            def tag = "${IMAGE_NAME}:${env.BUILD_NUMBER}"
            docker.image(tag).push()
            docker.image(tag).push('latest')
          }
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
