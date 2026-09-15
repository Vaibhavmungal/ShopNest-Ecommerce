# 🛍️ ShopNest E-Commerce — Kubernetes Deployment Guide

> A containerized PHP 8.2 & MySQL 8.0 e-commerce platform designed exclusively for **Kubernetes (k8s)** deployment with an automated **Jenkins CI/CD** pipeline.

---

## 🎨 System Architecture

![ShopNest Kubernetes Architecture](assets/images/architecture-diagram.jpg)

---

## 📋 Required Software & Tool Versions

| Tool / Software | Required Version | Purpose |
|---|---|---|
| **Kubernetes (k8s)** | `v1.24+` | Container Orchestration Cluster (Minikube / EKS / GKE / AKS) |
| **kubectl** | `v1.24+` | Kubernetes Command-Line Tool |
| **Docker** | `v20.10+` | Container Runtime & Image Builder |
| **PHP** | `v8.2+` | Web Application Core Engine |
| **MySQL** | `v8.0+` | Database Engine |
| **Jenkins** | `v2.400+` | CI/CD Automation Server |

---

## ☸️ Kubernetes Deployment Steps

All Kubernetes resources (ConfigMap, Secret, PVC, MySQL Deployment/Service, and Web App Deployment/Service) are defined in a single manifest: `k8s/deployment.yaml`.

### Step 1: Deploy Manifest to Kubernetes Cluster
```bash
kubectl apply -f k8s/deployment.yaml
```

### Step 2: Verify All Resources
```bash
kubectl get pods,svc,pvc,configmap,secret
```

### Step 3: Monitor Deployment Rollout
```bash
kubectl rollout status deployment/ecommerce-app --timeout=120s
```

### Step 4: Access Application Service
```bash
# Get the external IP or NodePort of the ecommerce-service
kubectl get svc ecommerce-service
```

---

## 🔑 Default Login Credentials

Deploy and instantly log in using pre-configured database seed accounts:

| Role | Email | Password |
|---|---|---|
| **Admin Panel** | `admin@shopnest.com` | `Admin@1234` |
| **Customer Storefront** | `rahul@example.com` | `Customer@123` |

---

## 🚀 Jenkins CI/CD Pipeline Workflow

The project includes an automated declarative `Jenkinsfile` with the following stages:

1. **PHP Lint**: Validates PHP syntax across all project source files.
2. **Build Docker Image**: Builds Docker image tagged with `${BUILD_NUMBER}` and `latest`.
3. **Push Docker Image**: Authenticates with Docker Hub using Jenkins credentials (`Docker`) and pushes the image.
4. **Deploy to Kubernetes**: Applies `k8s/deployment.yaml`, sets the new container image, and verifies rollout health.

---

## 📜 License & Author

- **License**: MIT
- **Repository**: [ShopNest-Ecommerce](https://github.com/Vaibhavmungal/ShopNest-Ecommerce)
