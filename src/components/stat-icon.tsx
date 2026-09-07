import { UsersRound, BookOpenCheck, MapPin, School, GraduationCap, Building, DoorOpen, Trophy, Heart, Star } from "lucide-react";

const icons: Record<string, React.ComponentType<{ className?: string }>> = {
  UsersRound, BookOpenCheck, MapPin, School, GraduationCap, Building, DoorOpen, Trophy, Heart, Star,
};

export function StatIcon({ name, className }: { name: string; className?: string }) {
  const Icon = icons[name] || UsersRound;
  return <Icon className={className} />;
}
