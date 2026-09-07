import { notFound } from "next/navigation";
import Link from "next/link";
import { CalendarDays, ArrowLeft } from "lucide-react";
import { prisma } from "@/lib/prisma";

async function getAnnouncement(slug: string) {
  try {
    return await prisma.announcement.findUnique({
      where: { slug, isPublished: true },
    });
  } catch {
    return null;
  }
}

export default async function AnnouncementDetailPage({
  params,
}: {
  params: { slug: string };
}) {
  const announcement = await getAnnouncement(params.slug);

  if (!announcement) notFound();

  const date = new Intl.DateTimeFormat("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  }).format(new Date(announcement.publishedAt));

  const content = announcement.content as Record<string, unknown>;

  return (
    <main className="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
      <Link
        href="/pengumuman"
        className="mb-8 inline-flex items-center gap-1.5 text-sm text-muted-foreground hover:text-primary"
      >
        <ArrowLeft className="h-4 w-4" />
        Kembali ke Pengumuman
      </Link>

      <article>
        <header className="mb-8">
          <span className="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
            {announcement.category}
          </span>
          <h1 className="mt-4 text-3xl font-bold">{announcement.title}</h1>
          <div className="mt-3 flex items-center gap-1.5 text-sm text-muted-foreground">
            <CalendarDays className="h-4 w-4" />
            {date}
            {announcement.authorName && (
              <span className="ml-3">oleh {announcement.authorName}</span>
            )}
          </div>
        </header>

        <div className="space-y-4 text-base leading-7 text-foreground">
          <TiptapRenderer content={content} />
        </div>
      </article>
    </main>
  );
}

function TiptapRenderer({ content }: { content: Record<string, unknown> }) {
  if (!content || !Array.isArray((content as { content?: unknown[] }).content)) {
    return <p className="text-muted-foreground">Konten tidak tersedia.</p>;
  }

  const nodes = (content as { content: TiptapNode[] }).content;
  return <>{nodes.map((node, i) => <RenderNode key={i} node={node} />)}</>;
}

type TiptapNode = {
  type: string;
  content?: TiptapNode[];
  text?: string;
  marks?: { type: string }[];
  attrs?: Record<string, unknown>;
};

function RenderNode({ node }: { node: TiptapNode }) {
  switch (node.type) {
    case "paragraph":
      return <p>{node.content?.map((child, i) => <RenderNode key={i} node={child} />)}</p>;
    case "heading": {
      const level = (node.attrs?.level as number) || 2;
      const Tag = `h${level}` as keyof JSX.IntrinsicElements;
      return <Tag>{node.content?.map((child, i) => <RenderNode key={i} node={child} />)}</Tag>;
    }
    case "bulletList":
      return <ul>{node.content?.map((child, i) => <RenderNode key={i} node={child} />)}</ul>;
    case "orderedList":
      return <ol>{node.content?.map((child, i) => <RenderNode key={i} node={child} />)}</ol>;
    case "listItem":
      return <li>{node.content?.map((child, i) => <RenderNode key={i} node={child} />)}</li>;
    case "blockquote":
      return <blockquote>{node.content?.map((child, i) => <RenderNode key={i} node={child} />)}</blockquote>;
    case "horizontalRule":
      return <hr />;
    case "hardBreak":
      return <br />;
    case "text": {
      let element: React.ReactNode = node.text;
      node.marks?.forEach((mark) => {
        if (mark.type === "bold") element = <strong>{element}</strong>;
        if (mark.type === "italic") element = <em>{element}</em>;
        if (mark.type === "strike") element = <s>{element}</s>;
        if (mark.type === "code") element = <code>{element}</code>;
      });
      return <>{element}</>;
    }
    default:
      return null;
  }
}
