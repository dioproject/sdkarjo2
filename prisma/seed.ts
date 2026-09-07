import 'dotenv/config';
import { PrismaClient } from '@prisma/client';
import { PrismaPg } from '@prisma/adapter-pg';

const adapter = new PrismaPg({ connectionString: process.env.DATABASE_URL! });
const prisma = new PrismaClient({ adapter });

async function hashPassword(password: string): Promise<string> {
  const encoder = new TextEncoder();
  const data = encoder.encode(password);
  const hash = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
}

async function main() {
  const email = 'sdnkarangrejo02official@gmail.com';
  const password = await hashPassword('sdkukarjo2');

  await prisma.user.upsert({
    where: { email },
    update: {},
    create: {
      email,
      password,
      name: 'Administrator'
    }
  });

  console.log(`✅ User admin created: ${email} / sdkukarjo2`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
