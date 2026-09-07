"use client";

import { Bold, Heading2, List } from "lucide-react";
import { EditorContent, useEditor } from "@tiptap/react";
import StarterKit from "@tiptap/starter-kit";
import Placeholder from "@tiptap/extension-placeholder";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

export function TiptapEditor({ inputName = "content" }: { inputName?: string }) {
  const [content, setContent] = useState("");
  const editor = useEditor({
    immediatelyRender: false,
    extensions: [
      StarterKit.configure({
        heading: {
          levels: [2, 3]
        }
      }),
      Placeholder.configure({
        placeholder: "Tulis isi pengumuman dengan jelas..."
      })
    ],
    content: {
      type: "doc",
      content: [{ type: "paragraph" }]
    },
    editorProps: {
      attributes: {
        class:
          "prose-content min-h-52 rounded-md border bg-background px-4 py-3 text-sm leading-7 outline-none focus:ring-2 focus:ring-ring"
      }
    },
    onUpdate({ editor }) {
      setContent(JSON.stringify(editor.getJSON()));
    }
  });

  useEffect(() => {
    if (editor && !content) {
      setContent(JSON.stringify(editor.getJSON()));
    }
  }, [content, editor]);

  const tools = [
    {
      label: "Heading",
      icon: Heading2,
      active: editor?.isActive("heading", { level: 2 }),
      onClick: () => editor?.chain().focus().toggleHeading({ level: 2 }).run()
    },
    {
      label: "Bold",
      icon: Bold,
      active: editor?.isActive("bold"),
      onClick: () => editor?.chain().focus().toggleBold().run()
    },
    {
      label: "List",
      icon: List,
      active: editor?.isActive("bulletList"),
      onClick: () => editor?.chain().focus().toggleBulletList().run()
    }
  ];

  return (
    <div className="space-y-3">
      <div className="flex gap-2">
        {tools.map((tool) => (
          <Button
            key={tool.label}
            type="button"
            variant="outline"
            size="icon"
            title={tool.label}
            className={cn(tool.active && "border-primary bg-primary text-primary-foreground")}
            onClick={tool.onClick}
          >
            <tool.icon className="h-4 w-4" />
          </Button>
        ))}
      </div>
      <EditorContent editor={editor} />
      <input type="hidden" name={inputName} value={content} />
    </div>
  );
}
