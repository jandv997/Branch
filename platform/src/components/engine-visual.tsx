"use client";

import { useEffect, useRef } from "react";
import * as THREE from "three";

export function EngineVisual() {
  const ref = useRef<HTMLDivElement>(null);
  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, el.clientWidth / el.clientHeight, 0.1, 100);
    camera.position.set(0, 0, 6);
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(el.clientWidth, el.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    el.appendChild(renderer.domElement);

    const group = new THREE.Group();
    scene.add(group);
    const rings = [1.4, 2.1, 2.8];
    rings.forEach((r, i) => {
      const geo = new THREE.TorusGeometry(r, 0.012, 8, 128);
      const mat = new THREE.MeshBasicMaterial({
        color: i === 1 ? 0x8b5cf6 : 0x22d3ee,
        transparent: true,
        opacity: 0.7,
      });
      const mesh = new THREE.Mesh(geo, mat);
      mesh.rotation.x = Math.PI / 2.6 + i * 0.2;
      group.add(mesh);
    });
    const core = new THREE.Mesh(
      new THREE.SphereGeometry(0.35, 32, 32),
      new THREE.MeshBasicMaterial({ color: 0x22d3ee }),
    );
    group.add(core);
    const dots = new THREE.Group();
    for (let i = 0; i < 24; i++) {
      const d = new THREE.Mesh(
        new THREE.SphereGeometry(0.03, 8, 8),
        new THREE.MeshBasicMaterial({ color: i % 2 ? 0x8b5cf6 : 0x22d3ee }),
      );
      const a = (i / 24) * Math.PI * 2;
      d.position.set(Math.cos(a) * 2.1, Math.sin(a * 1.3) * 0.4, Math.sin(a) * 2.1);
      dots.add(d);
    }
    group.add(dots);

    let raf = 0;
    const tick = () => {
      group.rotation.y += 0.003;
      dots.rotation.y -= 0.005;
      renderer.render(scene, camera);
      raf = requestAnimationFrame(tick);
    };
    tick();
    const onResize = () => {
      camera.aspect = el.clientWidth / el.clientHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(el.clientWidth, el.clientHeight);
    };
    window.addEventListener("resize", onResize);
    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener("resize", onResize);
      renderer.dispose();
      el.removeChild(renderer.domElement);
    };
  }, []);
  return <div ref={ref} className="h-[420px] w-full" aria-hidden />;
}
