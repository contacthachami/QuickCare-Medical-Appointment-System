## E. Architecture Système de QuickCare

L'architecture système de QuickCare représente une vue d'ensemble des différentes composantes et de leurs interactions, offrant une perspective globale de la solution. Cette architecture se distingue des diagrammes UML traditionnels en fournissant une vision plus abstraite et accessible aux parties prenantes non techniques.

```
┌─────────────────────────────────────────────────────────────────────────┐
│                            INTERFACE UTILISATEUR                         │
├───────────────┬───────────────────────────────┬─────────────────────────┤
│  PATIENTS     │          MÉDECINS             │     ADMINISTRATEURS     │
│               │                               │                         │
│ ┌───────────┐ │ ┌───────────────────────────┐ │ ┌─────────────────────┐ │
│ │Recherche  │ │ │Gestion du Calendrier      │ │ │Tableau de Bord      │ │
│ │de Médecins│ │ │                           │ │ │Administratif        │ │
│ └───────────┘ │ └───────────────────────────┘ │ └─────────────────────┘ │
│ ┌───────────┐ │ ┌───────────────────────────┐ │ ┌─────────────────────┐ │
│ │Prise de   │ │ │Gestion des Patients       │ │ │Gestion des          │ │
│ │Rendez-vous│ │ │                           │ │ │Utilisateurs         │ │
│ └───────────┘ │ └───────────────────────────┘ │ └─────────────────────┘ │
│ ┌───────────┐ │ ┌───────────────────────────┐ │ ┌─────────────────────┐ │
│ │Suivi des  │ │ │Consultation des           │ │ │Configuration        │ │
│ │Consultatio│ │ │Dossiers Médicaux          │ │ │Système              │ │
│ └───────────┘ │ └───────────────────────────┘ │ └─────────────────────┘ │
└───────────────┴───────────────────────────────┴─────────────────────────┘
             ▲                    ▲                      ▲
             │                    │                      │
             ▼                    ▼                      ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                             API GATEWAY                                  │
│                                                                         │
│    ┌────────────────┐   ┌─────────────────┐   ┌─────────────────────┐   │
│    │Authentication  │   │Autorisation     │   │Rate Limiting        │   │
│    └────────────────┘   └─────────────────┘   └─────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────┘
             ▲                    ▲                      ▲
             │                    │                      │
             ▼                    ▼                      ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                             SERVICES MÉTIER                             │
├──────────────────────┬──────────────────────┬──────────────────────────┤
│ ┌──────────────────┐ │ ┌────────────────────┐ │ ┌──────────────────────┐ │
│ │Service de        │ │ │Service de Gestion  │ │ │Service de            │ │
│ │Rendez-vous       │ │ │des Utilisateurs    │ │ │Notification          │ │
│ └──────────────────┘ │ └────────────────────┘ │ └──────────────────────┘ │
│ ┌──────────────────┐ │ ┌────────────────────┐ │ ┌──────────────────────┐ │
│ │Service           │ │ │Service de          │ │ │Service de            │ │
│ │Médecin           │ │ │Dossiers Médicaux   │ │ │Rapports              │ │
│ └──────────────────┘ │ └────────────────────┘ │ └──────────────────────┘ │
└──────────────────────┴──────────────────────┴──────────────────────────┘
             ▲                    ▲                      ▲
             │                    │                      │
             ▼                    ▼                      ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                           COUCHE DE PERSISTANCE                         │
├────────────────────────────┬──────────────────────┬────────────────────┤
│  ┌───────────────────────┐ │  ┌──────────────────┐ │  ┌────────────────┐ │
│  │Base de Données        │ │  │    Cache         │ │  │ Stockage de    │ │
│  │   MariaDB             │ │  │    Redis         │ │  │  Fichiers      │ │
│  └───────────────────────┘ │  └──────────────────┘ │  └────────────────┘ │
└────────────────────────────┴──────────────────────┴────────────────────┘
```

Cette architecture système illustre les 4 couches principales de QuickCare:

1. **Interface Utilisateur**: Divisée en trois portails distincts selon les profils d'utilisateurs (patients, médecins, administrateurs), chacun avec ses fonctionnalités spécifiques.

2. **API Gateway**: Couche intermédiaire qui gère l'authentification, l'autorisation et le contrôle du trafic, servant de point d'entrée unique pour toutes les requêtes.

3. **Services Métier**: Ensemble de services spécialisés qui implémentent la logique métier de l'application, organisés de façon modulaire pour faciliter la maintenance et l'évolution.

4. **Couche de Persistance**: Responsable du stockage et de la récupération des données, comprenant la base de données MariaDB pour les données structurées, Redis pour le cache et un système de stockage pour les fichiers.

Cette architecture modulaire offre plusieurs avantages:

-   Une séparation claire des responsabilités
-   Une évolutivité horizontale (possibilité d'ajouter des instances de services)
-   Une isolation des composants pour faciliter les tests et la maintenance
-   Une sécurité renforcée avec des contrôles à plusieurs niveaux

Le diagramme met en évidence les flux de données entre les différentes couches, montrant comment les requêtes utilisateurs sont traitées à travers le système, depuis l'interface jusqu'à la persistance des données.
