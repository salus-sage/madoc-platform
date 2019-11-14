# Design and implementation of API Gateway

* Status: **proposed**
* Deciders: **Stephen Fraser**, **Matt McGrattan**

Technical Story: [MAD-1](https://digirati.atlassian.net/browse/MAD-1) - We need to design a reverse proxy implementation that can handle the following API concerns:

- Authentication
- Caching

Are there pre-canned solutions?

## Context and Problem Statement

There are a variety of services across the Madoc platform, each implementing their own independent pieces of functionality.
These services all have some things in common, namely, they need to use the same **authentication** source, and they need to support **caching** on some level.

In addition to these two requirements, there are niceties that come "for free" with an API gateway implementation:

- Session Tracing - the ability to view all requests that flowed through the API gateway for a given session.
- Centralised statistics collection - a centralized location to aggregate statistics on per-endpoint and per-service requests.
- Centralised rate limiting and client management

## Decision Drivers <!-- optional -->

* Maintainability - It must be possible for developers working in the Cultural Heritage squad to extend the API Gateway with new functionality if required.
* Open Source - A FOSS solution is required.

## Considered Options

* [Kong](https://github.com/Kong/kong)
* [Istio](https://istio.io/)

<!-- TODO: Update when decision is made -->
<!--
## Decision Outcome

Chosen option: "[option 1]", because [justification. e.g., only option, which meets k.o. criterion decision driver | which resolves force force | … | comes out best (see below)].

### Positive Consequences

* [e.g., improvement of quality attribute satisfaction, follow-up decisions required, …]
* …

### Negative Consequences

* [e.g., compromising quality attribute, follow-up decisions required, …]
* …
-->

## Pros and Cons of the Options

### Kong

A collection of Lua scripts on top of NGINX that together form a pluggable API gateway solution.

* Maintenance - Bad - It's written in Lua, which lacks adequate tooling for developers.
* Configuration - Good - Supports declarative configuration without relying on an external data store.
* Open Source - Good - Development happens in the open. Outside contributions are welcome. There is an "Enterprise" edition, which offers additional features.
* Deployment - Good - Single OCI container that can be deployed to Docker.

### Istio

* Maintenance - Bad - It's written in Go, consists of many services.
* Open Source - Good - Development happens in the open. Outside contributions are welcome.
* Deployment - Bad - Cloud Native, and relies on Kubernetes quite a bit. Would need to be stripped back to de-k8s it.

### Nginx (with plugin) + Varnish

* Maintenance - Good - Can be written in anything that supports FFI with C (or alternatively, Lua).
* Configuration - Good - Rely on existing Nginx configuration file format.
* Deployment - Good - Extension on existing Nginx deployments.
* Open Source - N/A - Would be decided by us.

##### Authentication Approach

The approach used for authentication in this implementation would be reliant on a custom NGINX plugin, that would
be built and distributed as part of the Madoc image we ship today.
