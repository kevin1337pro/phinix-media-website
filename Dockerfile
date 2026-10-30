ARG NGINX_IMAGE=nginxinc/nginx-unprivileged:1.28-alpine@sha256:7377697a821c131a924a7105fafbe7414db4e9fcc77a6f08f776f33f141ec3f8
FROM ${NGINX_IMAGE}

COPY --chown=101:101 docker/nginx.conf /etc/nginx/conf.d/default.conf
# Public container builds only from the separately verified production export.
# The owner-only Sites preview in dist/ intentionally remains noindex.
COPY --chown=101:101 dist-production/ /usr/share/nginx/html/

USER 101:101

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD wget -q -O /dev/null http://127.0.0.1:8080/healthz || exit 1

