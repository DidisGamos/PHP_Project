💧⚡ Gestion de paiement d’eau et d’électricité

📌 Description
Ce projet est une application web développée en PHP avec une base de données MySQL permettant de gérer les paiements d’eau et d’électricité.
Elle offre des fonctionnalités complètes pour gérer les clients, leurs compteurs, les relevés, et les paiements, tout en automatisant le calcul des montants et en générant des factures PDF.

⚙ Installation

1️⃣ Cloner le dépôt

git clone https://github.com/DidisGamos/PHP_Project.git


2️⃣ Placer dans le serveur local

Sous XAMPP : dossier htdocs/

Sous WAMP : dossier www/


3️⃣ Créer la base de données

Ouvrir phpMyAdmin

Créer une base de données (ex. gestion_paiement)

Importer le fichier SQL fourni (database.sql ou similaire)


4️⃣ Configurer la connexion
Modifier le fichier config.php :

php
Copier
Modifier
$host = "localhost";
$user = "root";
$pass = "";
$db   = "gestion_paiement";


5️⃣ Lancer le projet

Accéder via :

http://localhost/PHP_Project/

📧 Notifications par e-mail

Configurez les paramètres SMTP dans mail_config.php (ou fichier équivalent)

Utilisez un compte e-mail valide (par exemple Gmail avec mot de passe d’application)

📦 Technologies utilisées

PHP 7.4+

MySQL / MariaDB

HTML5 / CSS3 / JavaScript

Bibliothèque FPDF pour PDF

PHPMailer pour l’envoi d’e-mails

👤 Auteur

Herllandys Amoros Christy RAZAFIMANDIMBY
📧 herllandysamoroschristy@gmail.com
