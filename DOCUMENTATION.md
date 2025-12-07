# 📚 Documentation Complète - QuickCare

## 📋 Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture du système](#architecture-du-système)
3. [Technologies utilisées](#technologies-utilisées)
4. [Structure du projet](#structure-du-projet)
5. [Base de données](#base-de-données)
6. [Modèles de données](#modèles-de-données)
7. [Contrôleurs](#contrôleurs)
8. [Routes et Middleware](#routes-et-middleware)
9. [Fonctionnalités principales](#fonctionnalités-principales)
10. [Sécurité](#sécurité)
11. [Installation et Configuration](#installation-et-configuration)
12. [API et Intégrations](#api-et-intégrations)

---

## 🎯 Vue d'ensemble

**QuickCare** est une application web de gestion de rendez-vous médicaux qui facilite la planification et la gestion des consultations médicales. Elle améliore l'efficacité et l'accessibilité des services médicaux pour les patients et les prestataires de soins de santé.

### Objectifs principaux

-   Simplifier la prise de rendez-vous médicaux
-   Optimiser la gestion du temps pour les médecins
-   Améliorer l'expérience patient
-   Centraliser les données médicales et administratives

---

## 🏗️ Architecture du système

### Architecture MVC (Model-View-Controller)

QuickCare suit le pattern MVC de Laravel avec une séparation claire des responsabilités :

```
┌─────────────┐
│   Views     │ ← Interface utilisateur (Blade Templates)
└──────┬──────┘
       │
┌──────▼──────┐
│ Controllers │ ← Logique métier et traitement des requêtes
└──────┬──────┘
       │
┌──────▼──────┐
│   Models    │ ← Interaction avec la base de données
└─────────────┘
```

### Types d'utilisateurs

L'application gère trois types d'utilisateurs distincts :

1. **Administrateur** : Gestion complète du système
2. **Médecin** : Gestion des rendez-vous et patients
3. **Patient** : Prise de rendez-vous et suivi médical

---

## 💻 Technologies utilisées

### Backend

-   **Framework** : Laravel 10.x (PHP 8.1+)
-   **Base de données** : MySQL/MariaDB
-   **ORM** : Eloquent
-   **Authentification** : Laravel Breeze + Sanctum
-   **Queue/Jobs** : Laravel Queue System

### Frontend

-   **Template Engine** : Blade
-   **CSS Framework** : Tailwind CSS 3.4
-   **JavaScript** :
    -   Alpine.js 3.4
    -   Vanilla JavaScript
-   **Build Tool** : Vite.js 5.0
-   **UI Components** : Flowbite 2.3

### Bibliothèques et Packages

-   **Charts** : Chart.js via ConsoleTV's Charts 6.x
-   **PDF Generation** : DomPDF (barryvdh/laravel-dompdf)
-   **Excel Export** : Maatwebsite Excel 3.1
-   **Icons** : FontAwesome, Blade Heroicons
-   **Geolocation** : Stevebauman Location 7.2
-   **Notifications** : SweetAlert2
-   **Tables** : DataTables.js
-   **Calendar** : FullCalendar 6.1
-   **Animations** : Animate.css

### Outils de développement

-   **IDE** : Visual Studio Code
-   **Server** : XAMPP
-   **Version Control** : Git/GitHub
-   **Package Managers** : Composer, NPM
-   **Testing** : Pest 2.0

---

## 📁 Structure du projet

```
QuickCare/
├── app/
│   ├── Charts/                 # Classes pour les graphiques
│   │   ├── AppoinmentsCharts.php
│   │   ├── DoctorCharts.php
│   │   └── PatientCharts.php
│   ├── Console/
│   │   ├── Kernel.php
│   │   └── Commands/           # Commandes Artisan personnalisées
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Exports/                # Classes d'exportation Excel
│   │   ├── AppointmentsExport.php
│   │   ├── DoctorPatientsExport.php
│   │   ├── PatientAppointmentsExport.php
│   │   └── TravelRecordsExport.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── DoctorController.php
│   │   │   ├── PatientController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── NotificationController.php
│   │   │   └── Auth/
│   │   ├── Middleware/
│   │   │   ├── Admin.php          # Protection routes admin
│   │   │   ├── Doctor.php         # Protection routes médecin
│   │   │   ├── Patient.php        # Protection routes patient
│   │   │   └── Checkers/          # Validations spécifiques
│   │   └── Requests/              # Form Requests
│   ├── Jobs/
│   │   └── UpdateAppointmentStatus.php  # Job automatique
│   ├── Mail/
│   │   ├── ApplicationSubmitted.php
│   │   └── Support.php
│   ├── Models/                    # Modèles Eloquent
│   │   ├── Address.php
│   │   ├── Application.php
│   │   ├── Appointment.php
│   │   ├── Doctor.php
│   │   ├── Notification.php
│   │   ├── Patient.php
│   │   ├── Rating.php
│   │   ├── Schedule.php
│   │   ├── Speciality.php
│   │   └── User.php
│   ├── Providers/
│   └── helpers.php                # Fonctions helper globales
├── bootstrap/
├── config/                        # Fichiers de configuration
│   ├── app.php
│   ├── database.php
│   ├── mail.php
│   ├── charts.php
│   └── ...
├── database/
│   ├── migrations/                # Migrations de la BDD
│   ├── seeders/                   # Seeders pour données test
│   └── factories/                 # Factories pour tests
├── public/                        # Assets publics
│   ├── css/
│   ├── js/
│   ├── img/
│   └── index.php
├── resources/
│   ├── css/
│   ├── js/
│   └── views/                     # Templates Blade
│       ├── auth/
│       ├── panels/
│       │   ├── admin/
│       │   ├── doctor/
│       │   └── patient/
│       ├── layouts/
│       ├── components/
│       └── ...
├── routes/
│   ├── web.php                    # Routes principales
│   ├── api.php
│   ├── auth.php
│   └── web/
│       ├── BackOffice/            # Routes administration
│       │   ├── admin.php
│       │   ├── doctor.php
│       │   ├── patient.php
│       │   ├── appointment.php
│       │   ├── schedules.php
│       │   ├── speciality.php
│       │   └── doctors-apply.php
│       └── FrontOffice/           # Routes utilisateurs
│           ├── patient/
│           ├── doctor/
│           └── others/
├── storage/                       # Stockage fichiers
├── tests/                         # Tests automatisés
├── vendor/                        # Dépendances PHP
├── .env                          # Variables d'environnement
├── composer.json                 # Dépendances PHP
├── package.json                  # Dépendances JavaScript
└── artisan                       # CLI Laravel
```

---

## 🗄️ Base de données

### Tables principales

#### 1. **users**

Table centrale pour tous les utilisateurs du système.

```sql
- id (PK)
- name
- email (unique)
- password
- gender
- phone
- address_id (FK → addresses)
- user_type (enum: 'admin', 'doctor', 'patient')
- img (photo de profil)
- email_verified_at
- remember_token
- timestamps
```

#### 2. **doctors**

Informations spécifiques aux médecins.

```sql
- id (PK)
- birth_date
- degree
- user_id (FK → users)
- specialty_id (FK → specialities)
- status
- timestamps
```

#### 3. **patients**

Informations spécifiques aux patients.

```sql
- id (PK)
- cin (carte d'identité nationale)
- birth_date
- user_id (FK → users)
- timestamps
```

#### 4. **appointments**

Gestion des rendez-vous médicaux.

```sql
- id (PK)
- reason
- status (Pending, Approved, Cancelled, Completed)
- appointment_date
- doctor_comment
- patient_id (FK → patients)
- doctor_id (FK → doctors)
- schedule_id (FK → schedules)
- check_in_time
- check_out_time
- travel_time_minutes
- Patient_Disponibility
- timestamps
```

#### 5. **schedules**

Horaires de disponibilité des médecins.

```sql
- id (PK)
- start (heure début)
- end (heure fin)
- status
- day (jour de la semaine)
- specific_date (date spécifique optionnelle)
- doctor_id (FK → doctors)
- timestamps
```

#### 6. **specialities**

Spécialités médicales.

```sql
- id (PK)
- name
- timestamps
```

#### 7. **ratings**

Évaluations des médecins par les patients.

```sql
- id (PK)
- patient_id (FK → patients)
- doctor_id (FK → doctors)
- rating (note)
- comment
- timestamps
```

#### 8. **notifications**

Système de notifications.

```sql
- id (PK)
- user_id (FK → users)
- title
- message
- read_at
- timestamps
```

#### 9. **applications**

Candidatures des médecins.

```sql
- id (PK)
- (champs de candidature)
- timestamps
```

#### 10. **addresses**

Adresses des utilisateurs.

```sql
- id (PK)
- (champs d'adresse)
- timestamps
```

### Relations entre tables

```
users (1) ──→ (1) doctors
users (1) ──→ (1) patients
users (1) ──→ (*) notifications
users (*) ──→ (1) addresses

doctors (1) ──→ (*) appointments
doctors (1) ──→ (*) schedules
doctors (1) ──→ (*) ratings
doctors (*) ──→ (1) specialities

patients (1) ──→ (*) appointments
patients (1) ──→ (*) ratings

appointments (*) ──→ (1) doctors
appointments (*) ──→ (1) patients
appointments (*) ──→ (1) schedules

specialities (1) ──→ (*) doctors
```

---

## 🎨 Modèles de données

### User Model

Le modèle central avec polymorphisme pour les types d'utilisateurs.

**Relations :**

-   `hasOne(Patient::class)` - Un utilisateur peut être un patient
-   `hasOne(Doctor::class)` - Un utilisateur peut être un médecin
-   `belongsTo(Address::class)` - Adresse de l'utilisateur
-   `hasMany(Notification::class)` - Notifications de l'utilisateur

**Attributs spéciaux :**

-   `dashboard_route` : Attribut dynamique qui retourne la route du tableau de bord selon le type d'utilisateur

### Doctor Model

**Relations :**

-   `belongsTo(User::class)` - Informations utilisateur
-   `belongsTo(Speciality::class)` - Spécialité médicale
-   `hasMany(Schedule::class)` - Horaires de disponibilité
-   `hasMany(Appointment::class)` - Rendez-vous
-   `hasMany(Rating::class)` - Évaluations reçues

**Champs fillable :**

-   birth_date, degree, user_id, specialty_id, status

### Patient Model

**Relations :**

-   `belongsTo(User::class)` - Informations utilisateur
-   `hasMany(Appointment::class)` - Rendez-vous
-   `hasMany(Rating::class)` - Évaluations données

**Champs fillable :**

-   cin, birth_date, user_id

### Appointment Model

**Relations :**

-   `belongsTo(Doctor::class)` - Médecin du rendez-vous
-   `belongsTo(Patient::class)` - Patient du rendez-vous
-   `belongsTo(Schedule::class)` - Créneau horaire

**Champs fillable :**

-   reason, status, appointment_date, doctor_comment, patient_id, doctor_id, schedule_id, check_in_time, check_out_time, travel_time_minutes

**Statuts possibles :**

-   Pending (En attente)
-   Approved (Approuvé)
-   Cancelled (Annulé)
-   Completed (Terminé)

### Schedule Model

**Relations :**

-   `belongsTo(Doctor::class)` - Médecin propriétaire
-   `hasMany(Appointment::class)` - Rendez-vous associés

**Champs fillable :**

-   start, end, status, day, doctor_id, specific_date

### Rating Model

**Relations :**

-   `belongsTo(Patient::class)` - Patient évaluateur
-   `belongsTo(Doctor::class)` - Médecin évalué

**Champs fillable :**

-   patient_id, doctor_id, rating, comment

### Speciality Model

**Relations :**

-   `hasMany(Doctor::class)` - Médecins de cette spécialité

**Champs fillable :**

-   name

### Notification Model

**Relations :**

-   `belongsTo(User::class)` - Destinataire

**Champs fillable :**

-   user_id, title, message, read_at

---

## 🎛️ Contrôleurs

### AdminController

Gestion complète du système par l'administrateur.

**Fonctionnalités principales :**

-   Dashboard avec statistiques
-   Gestion des médecins (CRUD)
-   Gestion des patients (CRUD)
-   Gestion des spécialités (CRUD)
-   Gestion des rendez-vous
-   Validation des candidatures de médecins
-   Envoi de notifications
-   Génération de rapports PDF
-   Analyse des temps de trajet des médecins
-   Protection contre les attaques XSS

**Méthodes clés :**

-   `index()` - Dashboard admin
-   `doctors()` - Liste des médecins
-   `patients()` - Liste des patients
-   `specialities()` - Gestion des spécialités
-   `apply_view()` - Candidatures de médecins
-   `doctor_notify_view($id)` - Notification médecin
-   `patient_notify_view($id)` - Notification patient
-   `doctorTravelTimes()` - Rapport temps de trajet

### DoctorController

Interface et fonctionnalités pour les médecins.

**Fonctionnalités principales :**

-   Dashboard médecin
-   Gestion des rendez-vous
-   Gestion des patients
-   Gestion des horaires
-   Suivi des évaluations
-   Export des données (PDF/CSV)
-   Suivi des temps de trajet
-   Check-in/Check-out des rendez-vous

**Méthodes clés :**

-   `index()` - Dashboard médecin
-   `appointments()` - Gestion rendez-vous avec pagination
-   `travelTracking()` - Suivi temps de trajet
-   `exportTravelRecordsPDF()` - Export PDF
-   `exportTravelRecordsCSV()` - Export CSV
-   `patients()` - Liste des patients
-   `schedules()` - Gestion horaires

### PatientController

Interface et fonctionnalités pour les patients.

**Fonctionnalités principales :**

-   Dashboard patient
-   Recherche de médecins
-   Prise de rendez-vous
-   Consultation des rendez-vous
-   Évaluation des médecins
-   Conseils de santé (News API)
-   Géolocalisation
-   Export des rendez-vous
-   Protection XSS

**Méthodes clés :**

-   `index()` - Dashboard patient
-   `doctors()` - Recherche médecins
-   `appointment($id)` - Prise de rendez-vous
-   `patient_appointments($id)` - Mes rendez-vous
-   `getTips()` - Conseils santé via API
-   `appointment_detail($id)` - Détails rendez-vous

### ProfileController

Gestion du profil utilisateur.

**Fonctionnalités :**

-   Modification des informations personnelles
-   Changement de mot de passe
-   Upload de photo de profil
-   Mise à jour de l'adresse

### NotificationController

Système de notifications.

**Fonctionnalités :**

-   Liste des notifications
-   Marquer comme lu
-   Envoi de notifications
-   Notifications en temps réel

---

## 🛣️ Routes et Middleware

### Structure des routes

Le système utilise une architecture modulaire avec séparation des routes :

#### BackOffice (Administration)

```php
routes/web/BackOffice/
├── admin.php          # Routes administrateur
├── doctor.php         # Gestion médecins (admin)
├── patient.php        # Gestion patients (admin)
├── appointment.php    # Gestion rendez-vous (admin)
├── schedules.php      # Gestion horaires (admin)
├── speciality.php     # Gestion spécialités
└── doctors-apply.php  # Candidatures médecins
```

#### FrontOffice

```php
routes/web/FrontOffice/
├── patient/
│   ├── patient.php
│   ├── appointment.php
│   ├── doctor.php
│   └── health-tips.php
├── doctor/
│   ├── doctor.php
│   ├── appointment.php
│   ├── schedule.php
│   ├── patient.php
│   └── reviews.php
└── others/
    ├── profile.php
    ├── notification.php
    ├── others.php
    └── mail.php
```

### Middleware

#### 1. Admin Middleware

Protection des routes administrateur.

```php
if (!auth()->check() || auth()->user()->user_type != 'admin') {
    abort(403);
}
```

**Usage :**

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes admin
});
```

#### 2. Doctor Middleware

Protection des routes médecin.

```php
if (!auth()->check() || auth()->user()->user_type != 'doctor') {
    abort(403);
}
```

**Usage :**

```php
Route::middleware(['auth', 'doctor'])->group(function () {
    // Routes médecin
});
```

#### 3. Patient Middleware

Protection des routes patient.

```php
if (!auth()->check() || auth()->user()->user_type != 'patient') {
    abort(403);
}
```

**Usage :**

```php
Route::middleware(['auth', 'patient'])->group(function () {
    // Routes patient
});
```

#### 4. Checkers Middleware

Validations spécifiques (dans `app/Http/Middleware/Checkers/`) :

-   `CheckDoctor` - Vérification existence médecin
-   `CheckPatientId` - Vérification patient autorisé
-   `CheckAppointment` - Vérification rendez-vous

---

## ⚙️ Fonctionnalités principales

### 1. Gestion des utilisateurs

#### Inscription et Authentification

-   Inscription multi-rôles (Patient/Médecin)
-   Connexion sécurisée avec Laravel Breeze
-   Vérification email
-   Reset de mot de passe
-   Remember me

#### Profils utilisateurs

-   Photo de profil
-   Informations personnelles
-   Adresse complète
-   Coordonnées

### 2. Gestion des rendez-vous

#### Pour les patients

-   Recherche de médecins par spécialité
-   Consultation des disponibilités
-   Prise de rendez-vous en ligne
-   Modification/Annulation
-   Historique des rendez-vous
-   Export PDF des rendez-vous

#### Pour les médecins

-   Vue calendrier des rendez-vous
-   Approbation/Rejet des demandes
-   Ajout de commentaires médicaux
-   Check-in/Check-out des patients
-   Suivi du temps de consultation
-   Statistiques et graphiques

#### Pour les administrateurs

-   Vue globale de tous les rendez-vous
-   Gestion des conflits
-   Rapports et statistiques
-   Export massif

### 3. Gestion des horaires

#### Horaires récurrents

-   Définition par jour de la semaine
-   Plages horaires multiples
-   Activation/Désactivation

#### Horaires spécifiques

-   Dates spéciales
-   Exceptions aux horaires réguliers
-   Congés et absences

### 4. Système d'évaluation

#### Évaluations des médecins

-   Note sur 5 étoiles
-   Commentaires textuels
-   Calcul de la moyenne
-   Affichage public des évaluations
-   Tri des médecins par note

### 5. Notifications

#### Types de notifications

-   Nouveau rendez-vous
-   Confirmation/Annulation
-   Rappels automatiques
-   Messages administratifs

#### Canaux de notification

-   In-app (base de données)
-   Email (SMTP configuré)
-   Notifications temps réel

### 6. Candidatures médecins

#### Processus de candidature

-   Formulaire de candidature
-   Upload de documents
-   Validation par admin
-   Notification de décision

### 7. Exports et Rapports

#### Formats supportés

-   PDF (DomPDF)
-   Excel/CSV (Maatwebsite Excel)

#### Types de rapports

-   Liste des rendez-vous
-   Historique patient
-   Statistiques médecin
-   Temps de trajet
-   Rapports financiers

### 8. Dashboard et Analytics

#### Dashboard Administrateur

-   Statistiques globales
-   Graphiques (Chart.js)
-   Activité récente
-   KPIs principaux

#### Dashboard Médecin

-   Rendez-vous du jour
-   Patients en attente
-   Prochains rendez-vous
-   Évaluations récentes

#### Dashboard Patient

-   Prochain rendez-vous
-   Historique
-   Conseils de santé
-   Médecins favoris

### 9. Intégration API externe

#### News API (Conseils de santé)

-   Récupération d'articles de santé
-   Affichage dans le dashboard patient
-   Mise en cache des résultats
-   Gestion des timeouts

#### Geolocation

-   Détection de la localisation
-   Calcul des distances
-   Recherche de médecins à proximité

### 10. Suivi des temps de trajet

#### Pour les médecins

-   Enregistrement check-in/check-out
-   Calcul automatique du temps
-   Export des données
-   Analyse des temps moyens

---

## 🔒 Sécurité

### Mesures de sécurité implémentées

#### 1. Protection CSRF

-   Tokens CSRF sur tous les formulaires
-   Middleware VerifyCsrfToken actif
-   Protection des requêtes POST/PUT/DELETE

#### 2. Protection XSS (Cross-Site Scripting)

Fonction de détection dans les contrôleurs :

```php
private function isXssAttackDetected(array $originalInputs, array $sanitizedInputs): bool
{
    foreach ($originalInputs as $index => $originalInput) {
        if ($originalInput !== $sanitizedInputs[$index]) {
            return true;
        }
    }
    return false;
}
```

-   Validation et sanitization des inputs
-   Échappement automatique dans Blade
-   Détection de scripts malveillants

#### 3. Protection SQL Injection

-   Utilisation d'Eloquent ORM
-   Prepared statements automatiques
-   Validation des inputs

#### 4. Protection Brute Force

-   Rate limiting sur les routes de connexion
-   Throttling des tentatives
-   Blocage temporaire après échecs

#### 5. Authentification sécurisée

-   Hashage des mots de passe (bcrypt)
-   Sessions sécurisées
-   Remember tokens
-   Logout sécurisé

#### 6. Autorisation par rôles

-   Middleware par type d'utilisateur
-   Vérification des permissions
-   Isolation des données

#### 7. Validation des données

-   Form Request Validation
-   Règles de validation strictes
-   Messages d'erreur personnalisés

#### 8. Sécurité des fichiers

-   Validation du type de fichier
-   Limitation de la taille
-   Stockage sécurisé
-   Liens symboliques

---

## 🚀 Installation et Configuration

### Prérequis

```
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB
- XAMPP/WAMP/LARAGON (optionnel)
```

### Étapes d'installation

#### 1. Cloner le dépôt

```bash
git clone https://github.com/contacthachami/QuickCare.git
cd QuickCare
```

#### 2. Installer les dépendances PHP

```bash
composer install
```

#### 3. Installer les dépendances JavaScript

```bash
npm install
```

#### 4. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

#### 5. Configuration de la base de données

Éditez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quickcare
DB_USERNAME=root
DB_PASSWORD=
```

#### 6. Créer la base de données

```bash
mysql -u root -e "CREATE DATABASE quickcare;"
```

#### 7. Exécuter les migrations

```bash
php artisan migrate:fresh --seed
```

Cette commande :

-   Crée toutes les tables
-   Insère les données de test (seeders)

#### 8. Créer le lien symbolique pour le storage

```bash
php artisan storage:link
```

#### 9. Compiler les assets

```bash
# Développement
npm run dev

# Production
npm run build
```

#### 10. Lancer le serveur

```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

#### 11. Lancer le scheduler (optionnel)

Pour les tâches automatiques (mise à jour des statuts de rendez-vous) :

```bash
php artisan schedule:work
```

### Configuration Email (SMTP)

Dans le fichier `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Note** : Pour Gmail, utilisez un "App Password" au lieu du mot de passe principal.

### Configuration API externe

#### News API (pour les conseils de santé)

1. Créer un compte sur https://newsapi.org/
2. Obtenir une clé API
3. L'utiliser dans `PatientController::getTips()`

### Comptes de test (après seed)

```
Administrateur:
- Email: admin@quickcare.com
- Password: password

Médecin:
- Email: doctor@quickcare.com
- Password: password

Patient:
- Email: patient@quickcare.com
- Password: password
```

---

## 🔌 API et Intégrations

### API Routes

Les routes API sont définies dans `routes/api.php` avec le préfixe `/api`.

#### Authentification

```
POST /api/login
POST /api/register
POST /api/logout
```

#### Rendez-vous

```
GET    /api/appointments
POST   /api/appointments
PUT    /api/appointments/{id}
DELETE /api/appointments/{id}
```

### Intégrations externes

#### 1. News API

**Endpoint** : `https://newsapi.org/v2/top-headlines`

**Utilisation** : Récupération d'articles de santé pour le dashboard patient

**Configuration** :

```php
$response = Http::timeout(60)->retry(3, 5000)->get('https://newsapi.org/v2/top-headlines', [
    'apiKey' => 'YOUR_API_KEY',
    'category' => 'health',
    'country' => 'us'
]);
```

#### 2. Geolocation API

**Package** : `stevebauman/location`

**Utilisation** : Détection de la localisation des utilisateurs

**Configuration** :

```php
use Stevebauman\Location\Facades\Location;

$location = Location::get($ip);
```

#### 3. OpenStreetMap (Geocoding)

**Utilisation** : Conversion adresses ↔ coordonnées

**Package** : Inclus via Geocoder

---

## 📊 Jobs et Tâches automatisées

### UpdateAppointmentStatus Job

**Fichier** : `app/Jobs/UpdateAppointmentStatus.php`

**Fonction** : Mise à jour automatique du statut des rendez-vous passés

**Logique** :

```php
// Récupère les rendez-vous en attente avec une date passée
$appointments = Appointment::where('status', 'Pending')
    ->whereDate('date', '<', Carbon::today())
    ->get();

// Change le statut en 'cancelled'
foreach ($appointments as $appointment) {
    $appointment->status = 'cancelled';
    $appointment->save();
}
```

**Planification** : Défini dans `app/Console/Kernel.php`

### Scheduler Laravel

Pour activer le scheduler en production, ajoutez cette ligne au crontab :

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

En développement, utilisez :

```bash
php artisan schedule:work
```

---

## 📈 Charts et Analytics

### Classes de Charts

Le projet utilise `consoletvs/charts` pour générer des graphiques :

#### 1. AppoinmentsCharts

Graphiques liés aux rendez-vous :

-   Rendez-vous par jour
-   Rendez-vous par statut
-   Tendances mensuelles

#### 2. DoctorCharts

Statistiques pour les médecins :

-   Nombre de patients
-   Rendez-vous complétés
-   Évaluations moyennes

#### 3. PatientCharts

Statistiques pour les patients :

-   Historique des rendez-vous
-   Médecins consultés

### Utilisation dans les contrôleurs

```php
use App\Charts\DoctorCharts;

$chart = new DoctorCharts;
$chart->labels(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven']);
$chart->dataset('Rendez-vous', 'line', [12, 15, 10, 8, 20]);
```

---

## 📤 Système d'export

### Classes d'export

#### 1. AppointmentsExport

Export de la liste des rendez-vous.

#### 2. DoctorPatientsExport

Export des patients d'un médecin spécifique.

#### 3. PatientAppointmentsExport

Export de l'historique d'un patient.

#### 4. TravelRecordsExport

Export des temps de trajet des médecins.

### Utilisation

**Excel/CSV** :

```php
return Excel::download(new AppointmentsExport, 'appointments.xlsx');
```

**PDF** :

```php
$pdf = Pdf::loadView('exports.appointments', $data);
return $pdf->download('appointments.pdf');
```

---

## 🎨 Interface utilisateur

### Composants UI

Le projet utilise plusieurs bibliothèques pour l'interface :

#### Tailwind CSS

Framework CSS utility-first pour le styling.

#### Alpine.js

Framework JavaScript léger pour l'interactivité.

#### Flowbite

Composants UI basés sur Tailwind.

#### SweetAlert2

Alertes et modales élégantes.

#### DataTables

Tables interactives avec pagination, recherche, tri.

#### FullCalendar

Affichage des rendez-vous en format calendrier.

### Layouts

**Layout principal** : `resources/views/layouts/app.blade.php`

**Composants réutilisables** : `resources/views/components/`

---

## 🧪 Tests

### Structure des tests

```
tests/
├── Feature/       # Tests d'intégration
│   ├── AuthTest.php
│   ├── AppointmentTest.php
│   └── ...
└── Unit/          # Tests unitaires
    ├── UserTest.php
    └── ...
```

### Lancer les tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=AppointmentTest

# Avec couverture
php artisan test --coverage
```

---

## 🐛 Débogage et Logs

### Logs

Les logs sont stockés dans `storage/logs/laravel.log`

**Niveaux de log configurables** dans `.env` :

```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Outils de débogage

-   **Laravel Debugbar** (optionnel en dev)
-   **Telescope** (optionnel pour monitoring)
-   **Browser DevTools**

---

## 🔄 Workflow de développement

### Git Workflow

```bash
# Créer une branche feature
git checkout -b feature/nouvelle-fonctionnalite

# Faire des commits
git add .
git commit -m "Description du changement"

# Pusher la branche
git push origin feature/nouvelle-fonctionnalite

# Créer une Pull Request sur GitHub
```

### Conventions de code

-   **PSR-12** pour PHP
-   **ESLint** pour JavaScript
-   **Prettier** pour le formatage

---

## 📝 Fonctionnalités futures

### Roadmap

-   [ ] Application mobile (React Native / Flutter)
-   [ ] Téléconsultation vidéo
-   [ ] Système de paiement en ligne
-   [ ] Prescription électronique
-   [ ] Dossier médical électronique
-   [ ] Multi-langue (i18n)
-   [ ] API REST complète
-   [ ] Intégration calendrier Google/Outlook
-   [ ] Chat en temps réel
-   [ ] Notifications push
-   [ ] Analytics avancées

---

## 🤝 Contribution

### Comment contribuer

1. Fork le projet
2. Créer une branche feature
3. Commiter les changements
4. Pusher vers la branche
5. Ouvrir une Pull Request

### Guidelines

-   Suivre les conventions de code
-   Écrire des tests
-   Documenter les changements
-   Utiliser des messages de commit clairs

---

## 📞 Support et Contact

### Ressources

-   **Documentation Laravel** : https://laravel.com/docs
-   **GitHub Issues** : Pour reporter des bugs
-   **Email support** : hachamimehdi2005@gmail.com

---

## 📄 Licence

Ce projet est sous licence MIT.

---

## 👨‍💻 Auteur

**Hachami Mehdi**

-   Email : hachamimehdi2005@gmail.com
-   GitHub : [@contacthachami](https://github.com/contacthachami)

---

## 🙏 Remerciements

-   Laravel Framework
-   Communauté Open Source
-   Tous les contributeurs

---

**Version de la documentation** : 1.0.0  
**Dernière mise à jour** : Décembre 2025

---

## 📚 Annexes

### Commandes Artisan utiles

```bash
# Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Base de données
php artisan migrate
php artisan migrate:fresh
php artisan migrate:rollback
php artisan db:seed

# Queue
php artisan queue:work
php artisan queue:listen

# Optimisation
php artisan optimize
php artisan route:cache
php artisan config:cache
php artisan view:cache

# Maintenance
php artisan down
php artisan up
```

### Structure des variables d'environnement

```env
# Application
APP_NAME=QuickCare
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quickcare
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

_Cette documentation est un document vivant et sera mise à jour régulièrement._
