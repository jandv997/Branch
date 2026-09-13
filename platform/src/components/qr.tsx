"use client";

import { useEffect, useState } from "react";
import QRCode from "qrcode";

export function QrImg({ value, size = 96 }: { value: string; size?: number }) {
  const [src, setSrc] = useState<string>("");
  useEffect(() => {
    QRCode.toDataURL(value, { width: size, margin: 1, color: { dark: "#22D3EE", light: "#0A0B14" } }).then(setSrc);
  }, [value, size]);
  if (!src) return <div style={{ width: size, height: size }} className="bg-graphite-800" />;
  return <img src={src} width={size} height={size} alt="Referral QR" />;
}
