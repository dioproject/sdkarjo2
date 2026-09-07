export type AnnouncementCategory = "Akademik" | "Kegiatan" | "Prestasi" | "Informasi";

export type Announcement = {
  id: string;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  category: AnnouncementCategory;
  publishedAt: string;
  isPublished: boolean;
};

export type Teacher = {
  name: string;
  role: string;
  photo: string;
};
