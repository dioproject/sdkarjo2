# =============================================================================
# Plan C: Runtime-only Dockerfile.
# Asumsi: bun install + bun run build sudah dilakukan di HOST (di luar Docker).
# Image ini hanya copy artifacts pre-built. Ringan, build cepat (~30 detik).
# =============================================================================

FROM oven/bun:1.3.14
WORKDIR /app

ENV NODE_ENV=production
ENV NEXT_TELEMETRY_DISABLED=1
ENV PORT=3000
ENV HOSTNAME=0.0.0.0

# Install curl untuk healthcheck
RUN apt-get update && apt-get install -y curl && rm -rf /var/lib/apt/lists/*

# Buat user non-root
RUN groupadd --system --gid 1001 nodejs \
    && useradd  --system --uid 1001 --gid nodejs --create-home nextjs

# COPY pre-built standalone server (dari host: bun run build)
# .next/standalone berisi server.js + minimal deps
COPY --chown=nextjs:nodejs .next/standalone/ ./
COPY --chown=nextjs:nodejs .next/static/ ./.next/static/
COPY --chown=nextjs:nodejs public/ ./public/

# Prisma: schema + generated client engine
COPY --chown=nextjs:nodejs prisma/schema.prisma ./prisma/schema.prisma
COPY --chown=nextjs:nodejs node_modules/.prisma/ ./node_modules/.prisma/
COPY --chown=nextjs:nodejs node_modules/@prisma/ ./node_modules/@prisma/

# Direktori uploads (jadi volume di docker-compose)
RUN mkdir -p ./public/uploads/gallery ./public/uploads/teachers ./public/uploads/logo \
    && chown -R nextjs:nodejs ./public/uploads

USER nextjs

EXPOSE 3000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD curl -fsS http://127.0.0.1:3000/ || exit 1

CMD ["bun", "run", "server.js"]
