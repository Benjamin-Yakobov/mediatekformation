@echo off
REM Arreter jenkins
net stop jenkins
REM Changer le répertoire où Keycloak est installé
cd C:\keycloak-19.0.1\bin
REM Démarrer le serveur Keycloak (en mode dev)
.\kc.bat start-dev