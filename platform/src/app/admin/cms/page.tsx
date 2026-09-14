"use client";

import { Button, Card, Input, Label, Textarea } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useState } from "react";

export default function CmsPage() {
  const list = trpc.admin.cmsList.useQuery();
  const put = trpc.admin.cmsPut.useMutation({ onSuccess: () => list.refetch() });
  const [slug, setSlug] = useState("faq");
  const [title, setTitle] = useState("FAQ");
  const [body, setBody] = useState('[{"q":"Are daily credits guaranteed?","a":"No. They are capped (“up to”)."}]');
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">CMS</h1>
      <Card className="space-y-3 max-w-xl">
        <div>
          <Label>Slug</Label>
          <Input value={slug} onChange={(e) => setSlug(e.target.value)} />
        </div>
        <div>
          <Label>Title</Label>
          <Input value={title} onChange={(e) => setTitle(e.target.value)} />
        </div>
        <div>
          <Label>Body JSON</Label>
          <Textarea value={body} onChange={(e) => setBody(e.target.value)} />
        </div>
        <Button onClick={() => put.mutate({ slug, title, body: JSON.parse(body) })}>Save</Button>
      </Card>
      {(list.data ?? []).map((p) => (
        <Card key={p.id} className="text-xs">
          {p.slug} · {p.title}
        </Card>
      ))}
    </div>
  );
}
