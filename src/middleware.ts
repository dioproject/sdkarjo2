import { NextRequest, NextResponse } from 'next/server';
import { jwtVerify } from 'jose';

const SECRET = new TextEncoder().encode(process.env.JWT_SECRET || 'default-secret-change-me');

export async function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;

  // Skip login page
  if (pathname === '/admin/login') {
    const token = request.cookies.get('admin-token')?.value;
    if (token) {
      try {
        await jwtVerify(token, SECRET);
        return NextResponse.redirect(new URL('/admin', request.url));
      } catch {}
    }
    const res = NextResponse.next();
    res.headers.set('x-pathname', pathname);
    return res;
  }

  // Protect /admin routes
  const token = request.cookies.get('admin-token')?.value;
  if (!token) {
    return NextResponse.redirect(new URL('/admin/login', request.url));
  }

  try {
    await jwtVerify(token, SECRET);
    const res = NextResponse.next();
    res.headers.set('x-pathname', pathname);
    return res;
  } catch {
    return NextResponse.redirect(new URL('/admin/login', request.url));
  }
}

export const config = {
  matcher: ['/admin/:path*']
};
