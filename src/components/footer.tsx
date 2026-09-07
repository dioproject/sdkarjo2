import { schoolProfile } from "@/lib/dummy-data";

export function Footer() {
  return (
    <footer className="border-t bg-card">
      <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <p className="text-center text-sm text-muted-foreground">
          &copy; {new Date().getFullYear()} {schoolProfile.name}. Hak cipta dilindungi.
        </p>
      </div>
    </footer>
  );
}
