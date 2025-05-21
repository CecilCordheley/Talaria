# 🪽 Talaria – Prototype PHP

**Talaria** est un outil de gestion de demandes interservices, pensé pour des structures complexes comme les centres de relation client, les banques mutualistes ou les grandes organisations.

---

## 🚧 Version actuelle

> ⚠️ Ce dépôt contient une version *brouillon* développée en **PHP** avec **Bootstrap**.  
> Objectif : tester les flux, valider les idées et itérer rapidement.  
> ⚙️ Il ne s'agit pas encore d'une version destinée à la production.

---

## 🧠 Fonctionnalités principales

- Création de tickets par un agent de service client
- Validation par le manager du service émetteur
- Transmission vers un service transverse
- Assignation à un agent par un manager transverse
- Suivi d’état structuré (en base)
- Requalification encadrée : possibilité de rediriger vers un autre service ou de modifier la priorité, avec traçabilité
- Système de **licences d’exception** : pour autoriser certaines actions sensibles (ex. suppression d’un état)

---

## 🔎 En réflexion

- Affinage des rôles et droits (agent, manager, transverse, admin)
- Expérience utilisateur (UX) autour du cycle de vie des tickets
- Limitation des requalifications pour préserver la fluidité des demandes
- Interface plus robuste en JS (ex. manipulation DOM sur événements)

---

## 📁 Structure technique

- Langage : PHP procédural + Bootstrap
- BDD : MySQL (script SQL inclus)
- Organisation MVC légère
- Frontend minimal (prototype orienté logique métier)

---

## 📸 Aperçu

Pas encore de démo en ligne – à venir dans une version plus aboutie.  
> Si vous souhaitez tester ou contribuer, clonez le repo et installez-le en local.

---

## 🤝 Contribuer ou discuter

Ce projet est en cours de maturation.  
Pour échanger ou collaborer : [LinkedIn](https://www.linkedin.com/in/s%C3%A9bastien-damart-1578a142/) ou via la section Issues.

---

## 📜 Licence

Projet en phase de test, publié en accès libre. Licence à définir pour la version stable.

---
