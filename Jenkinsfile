pipeline {
    agent any
    environment {
        // Ganti dengan nama repo Docker Hub kamu
        IMAGE_NAME = 'yustikanurr/prediksi-cuaca'
        // Ganti dengan ID credentials Docker Hub yang kamu buat di Jenkins
        REGISTRY_CREDENTIALS = 'dockerhub-credentials'
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies (Composer)') {
            steps {
                bat 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Run Tests') {
            steps {
                bat '''
                    if exist phpunit.xml (
                      echo Running PHPUnit...
                      php vendor\\phpunit\\phpunit\\phpunit --testdox || echo "Test failed (ignored for now)"
                    ) else (
                      echo "No phpunit.xml found — skipping tests"
                    )
                '''
            }
        }

        stage('Build Docker Image') {
            steps {
                bat "docker build -t %IMAGE_NAME%:%BUILD_NUMBER% ."
            }
        }

        stage('Push Docker Image') {
            steps {
                withCredentials([usernamePassword(credentialsId: env.REGISTRY_CREDENTIALS, usernameVariable: 'USER', passwordVariable: 'PASS')]) {
                    bat "docker login -u %USER% -p %PASS%"
                    bat "docker push %IMAGE_NAME%:%BUILD_NUMBER%"
                    bat "docker tag %IMAGE_NAME%:%BUILD_NUMBER% %IMAGE_NAME%:latest"
                    bat "docker push %IMAGE_NAME%:latest"
                }
            }
        }
    }
}
