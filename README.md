# 🛍️ ShopNest — Kubernetes E-Commerce Platform

> A containerized e-commerce application built with **PHP 8.2** and **MySQL 8.0**, deployed on **Kubernetes (k8s)** using an automated **Jenkins CI/CD Pipeline**.

---

## 🎨 Architecture Diagram

![Kubernetes Architecture Diagram](assets/images/architecture-diagram.jpg)

---

## 🛠️ Tools & Technologies Used

- **Application & Runtime:** PHP 8.2 (Apache 2.4 web server)
- **Database:** MySQL 8.0 (Persistent volume storage via PVC)
- **Containerization:** Docker & Docker Hub
- **Orchestration:** Kubernetes (`kubectl`, ConfigMap, Secret, PVC, Deployments, Services)
- **CI/CD Automation:** Jenkins (Declarative Pipeline)
- **Version Control:** Git & GitHub

---

## 📋 Required Software & Versions

| Tool / Software | Required Version | Purpose |
|---|---|---|
| **Kubernetes (`kubectl`)** | `v1.24+` | Container orchestration and deployment management |
| **Docker Engine** | `20.10+` | Container image building and execution |
| **Jenkins Server** | `2.400+` | CI/CD automation pipeline agent |
| **PHP** | `8.2+` | Syntax linting and application core runtime |
| **MySQL** | `8.0+` | Database engine for e-commerce store |

---

## 🚀 Deployment Steps (Kubernetes)

### 1. Prerequisites Checklist
- A running Kubernetes cluster (Minikube / EKS / AKS / GKE / Kind).
- `kubectl` CLI installed and configured (`~/.kube/config`).

### 2. Deploy via Manifest

Apply the unified Kubernetes deployment manifest:

```bash
kubectl apply -f k8s/deployment.yaml
```

### 3. Verify Deployment & Resources

Check the status of created pods, services, PVCs, ConfigMaps, and Secrets:

```bash
kubectl get pods,svc,pvc,configmap,secret
```

Wait until all pods (`mysql` and `ecommerce-app`) show `Running` status:

```bash
kubectl rollout status deployment/ecommerce-app --timeout=120s
```

### 4. Access the Storefront

Get the external IP / Port for the LoadBalancer service:

```bash
kubectl get service ecommerce-service
```

Open `http://<EXTERNAL-IP-OR-NODE-IP>:80` in your web browser.

---

## 🔑 Default Login Credentials

| Role | Email | Password |
|---|---|---|
| **Admin Panel** | `admin@shopnest.com` | `Admin@1234` |
| **Customer Account** | `rahul@example.com` | `Customer@123` |

---

## ⚙️ Jenkins CI/CD Pipeline Flow

1. **PHP Lint Stage:** Scans PHP files for syntax errors using `php -l`.
2. **Build Docker Image:** Builds `$DOCKER_USER/aws-ecommerce:${BUILD_NUMBER}` and `latest`.
3. **Push Docker Image:** Authenticates to Docker Hub and pushes the generated image.
4. **Deploy to Kubernetes:** Applies `k8s/deployment.yaml` and executes `kubectl set image` for zero-downtime rolling updates.
