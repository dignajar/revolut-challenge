# Revolut Challenge

## Technologies
- Amazon AWS
- AWS Route53 for DNS
- AWS EC2 Load Balancer
- AWS EC2 instances
- AWS EC2 Auto-scaling
- AWS RDS for database
- Docker for containers
- Kubernetes for orchestration of containers
- Private Docker registry
- API made in PHP

## Pre-requirements
- Kubernetes running
- Docker registry running. Endpoint `docker.registry.com`
- RDS PostgreSQL master and slave
- Database with a table
```
CREATE TABLE users(
        username VARCHAR (100) UNIQUE NOT NULL,
        dateOfBirth TIMESTAMP NOT NULL
);
```

## Installation
Create the Docker image and push to your Docker registry.
```
$ cd app
$ docker build -t docker.registry.com/hello:1.0.0 .
$ docker push docker.registry.com/hello:1.0.0
```

Deploy all descriptors files to Kubernetes.
```
$ cd k8s
$ kubectl apply -f *
```

## Infrastructure diagram
![revolut](https://github.com/dignajar/revolut-challenge/raw/master/revolut.png)