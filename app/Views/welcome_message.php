<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIM — Galaxy Interactive</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
  font-family:'Inter',sans-serif;
  background:#040714;
  color:#e2e8f0;
  min-height:100vh;
  overflow:hidden;
  position:relative;
  display:flex;
  align-items:center;
  justify-content:center;
}
canvas#galaxy3d {
  position:fixed;
  inset:0;
  width:100%;
  height:100%;
  z-index:0;
}
.glow-cursor {
  position:fixed;
  width:360px;
  height:360px;
  border-radius:50%;
  background:radial-gradient(circle, rgba(79,142,247,.18), transparent 65%);
  pointer-events:none;
  transform:translate(-50%,-50%);
  z-index:2;
}
.content {
  position:relative;
  z-index:3;
  text-align:center;
  max-width:860px;
  width:100%;
  padding:2.5rem 1.5rem;
}
.logo {
  width:76px; height:76px;
  margin:0 auto 1.2rem;
  border-radius:20px;
  background:linear-gradient(135deg,#4f8ef7,#7c5cfc);
  display:flex; align-items:center; justify-content:center;
  font-size:32px;
  box-shadow:0 0 40px rgba(79,142,247,.5), 0 0 80px rgba(124,92,252,.25);
}
h1.sim-title {
  font-size:2.8rem;
  font-weight:800;
  background:linear-gradient(100deg,#fff 30%,#7c5cfc 80%);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
  margin-bottom:.4rem;
  letter-spacing:-.02em;
}
.sim-sub {
  color:#64748b;
  font-size:.95rem;
  margin-bottom:2.5rem;
}
.grid {
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:18px;
}
@media(max-width:540px){ .grid{ grid-template-columns:1fr; } }
.card {
  background:rgba(13,20,53,.45);
  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
  border-radius:18px;
  padding:28px 24px;
  text-align:left;
  text-decoration:none;
  color:#e2e8f0;
  border:1px solid rgba(255,255,255,.07);
  cursor:pointer;
  position:relative;
  overflow:hidden;
  display:block;
  transition:transform .4s cubic-bezier(.22,1,.36,1), box-shadow .4s;
}
.card:hover {
  transform:translateY(-14px) scale(1.04);
  box-shadow:0 0 50px rgba(79,142,247,.38), 0 0 100px rgba(124,92,252,.22);
}
.card-shine {
  position:absolute;
  inset:0;
  background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.12),transparent 70%);
  transform:translateX(-120%);
  transition:transform .6s ease;
}
.card:hover .card-shine { transform:translateX(120%); }
.card-icon {
  font-size:2.2rem;
  margin-bottom:10px;
  display:block;
}
.card-title { font-weight:700; font-size:1.05rem; margin-bottom:4px; }
.card-desc { font-size:.85rem; color:#64748b; }
.footer { margin-top:2rem; font-size:.75rem; color:#334155; }
.tilt-wrap { perspective:900px; }
.tilt-inner { transform-style:preserve-3d; transition:transform .2s ease-out; }
</style>
</head>
<body>

<canvas id="galaxy3d"></canvas>
<div class="glow-cursor" id="glowCursor"></div>

<div class="content">
  <div class="logo" id="logoEl">📊</div>
  <h1 class="sim-title">SIM Launchpad</h1>
  <p class="sim-sub">Sales Information Management System</p>

  <div class="grid">
    <div class="tilt-wrap">
      <a href="<?= base_url('/import') ?>" class="card tilt-inner">
        <div class="card-shine"></div>
        <span class="card-icon">☁️</span>
        <div class="card-title">Import Data</div>
        <div class="card-desc">Upload &amp; sinkronisasi data pesanan</div>
      </a>
    <!-- Dashboard Pencairan -->
    <div class="tilt-wrap">
      <a href="<?= base_url('/withdrawal') ?>" class="card tilt-inner" style="border-left: 4px solid #f59e0b;">
        <div class="card-shine"></div>
        <span class="card-icon">💰</span>
        <div class="card-title">Pencairan Dana</div>
        <div class="card-desc">Hak akses CEO. Kelola riwayat pendapatan dan pencairan dana pesanan selesai.</div>
      </a>
    </div>

    <!-- Modul Baru: Finance -->
    <div class="tilt-wrap">
      <a href="<?= base_url('/finance/pengeluaran') ?>" class="card tilt-inner" style="border-left: 4px solid #4f8ef7;">
        <div class="card-shine"></div>
        <span class="card-icon">💳</span>
        <div class="card-title">Finance & Kas</div>
        <div class="card-desc">Manajemen arus kas, pengeluaran operasional, dan pelunasan gaji.</div>
      </a>
    </div>

    <!-- Modul Baru: HRD -->
    <div class="tilt-wrap">
      <a href="<?= base_url('/hrd/karyawan') ?>" class="card tilt-inner" style="border-left: 4px solid #7c5cfc;">
        <div class="card-shine"></div>
        <span class="card-icon">👥</span>
        <div class="card-title">HRD & Payroll</div>
        <div class="card-desc">Kelola data karyawan, absensi harian, dan sistem penggajian otomatis.</div>
      </a>
    </div>
  </div>

  <div class="footer">SIM System &copy; <?= date('Y') ?></div>
</div>

<!-- Three.js r128 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<!-- GSAP 3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
(function(){

  /* ── THREE.JS 3D Galaxy ─────────────────────────── */
  const canvas   = document.getElementById('galaxy3d');
  const glowCursor = document.getElementById('glowCursor');

  const renderer = new THREE.WebGLRenderer({ canvas, alpha:true, antialias:true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, window.innerWidth/window.innerHeight, 0.1, 1000);
  camera.position.set(0, 2.5, 6);
  camera.lookAt(0, 0, 0);

  /* Stars */
  const starGeo = new THREE.BufferGeometry();
  const starCount = 3500;
  const starPos = new Float32Array(starCount * 3);
  for (let i = 0; i < starCount * 3; i++) starPos[i] = (Math.random() - .5) * 120;
  starGeo.setAttribute('position', new THREE.BufferAttribute(starPos, 3));
  const starMat = new THREE.PointsMaterial({ color:0xffffff, size:.18, sizeAttenuation:true, transparent:true, opacity:.8 });
  scene.add(new THREE.Points(starGeo, starMat));

  /* Galaxy arm builder */
  function makeGalaxyArm(count, armAngle, color) {
    const geo       = new THREE.BufferGeometry();
    const positions = new Float32Array(count * 3);
    const colors    = new Float32Array(count * 3);
    const c = new THREE.Color(color);

    for (let i = 0; i < count; i++) {
      const t      = i / count;
      const radius = t * 3.8;
      const spin   = radius * 2.2;
      const rand   = Math.pow(Math.random(), .8) * (Math.random() < .5 ? 1 : -1);
      const randY  = rand * .25;
      const angle  = t * Math.PI * 4 + spin + armAngle;

      positions[i*3]   = Math.cos(angle) * radius + rand * .3;
      positions[i*3+1] = randY;
      positions[i*3+2] = Math.sin(angle) * radius + rand * .3;

      const mixed = new THREE.Color().lerpColors(c, new THREE.Color(0xffffff), t * .4);
      colors[i*3] = mixed.r; colors[i*3+1] = mixed.g; colors[i*3+2] = mixed.b;
    }

    geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geo.setAttribute('color',    new THREE.BufferAttribute(colors, 3));

    const mat = new THREE.PointsMaterial({
      size:           .055,
      sizeAttenuation:true,
      vertexColors:   true,
      transparent:    true,
      opacity:        .9
    });
    return new THREE.Points(geo, mat);
  }

  const galaxy = new THREE.Group();
  galaxy.add(makeGalaxyArm(9000, 0,             0x4f8ef7)); // blue
  galaxy.add(makeGalaxyArm(9000, Math.PI,       0x7c5cfc)); // purple
  galaxy.add(makeGalaxyArm(6000, Math.PI * .5,  0x00f0ff)); // cyan
  galaxy.add(makeGalaxyArm(6000, Math.PI * 1.5, 0xa855f7)); // violet

  /* Core glow sphere */
  galaxy.add(new THREE.Mesh(
    new THREE.SphereGeometry(.18, 16, 16),
    new THREE.MeshBasicMaterial({ color:0x8bb4ff })
  ));

  scene.add(galaxy);

  /* ── Mouse Parallax ─────────────────────────────── */
  let mx = 0, my = 0, tx = 0, ty = 0;

  document.addEventListener('mousemove', e => {
    mx = (e.clientX / window.innerWidth  - .5);
    my = (e.clientY / window.innerHeight - .5);
    glowCursor.style.left = e.clientX + 'px';
    glowCursor.style.top  = e.clientY + 'px';
  });

  /* ── Resize ─────────────────────────────────────── */
  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });

  /* ── Render Loop ────────────────────────────────── */
  (function tick(){
    requestAnimationFrame(tick);

    galaxy.rotation.y += .0018;
    galaxy.rotation.x += .0003;

    // Smooth parallax
    tx += (mx - tx) * .04;
    ty += (my - ty) * .04;
    camera.position.x = tx * 1.8;
    camera.position.y = 2.5 - ty * 1.2;
    camera.lookAt(0, 0, 0);

    renderer.render(scene, camera);
  })();

  /* ── GSAP Entrance ──────────────────────────────── */
  const logo = document.getElementById('logoEl');
  gsap.set([logo, '.sim-title', '.sim-sub', '.card'], { opacity:0, y:30 });
  gsap.to(logo,         { opacity:1, y:0, duration:1,   ease:'expo.out', delay:.2  });
  gsap.to('.sim-title', { opacity:1, y:0, duration:1,   ease:'expo.out', delay:.4  });
  gsap.to('.sim-sub',   { opacity:1, y:0, duration:1,   ease:'expo.out', delay:.55 });
  gsap.to('.card',      { opacity:1, y:0, duration:.9,  ease:'expo.out', stagger:.15, delay:.7 });

  // Logo float loop
  gsap.to(logo, { y:-14, repeat:-1, yoyo:true, duration:2.8, ease:'sine.inOut', delay:1.2 });

  /* ── 3D Card Tilt ───────────────────────────────── */
  document.querySelectorAll('.tilt-wrap').forEach(wrap => {
    const inner = wrap.querySelector('.tilt-inner');
    wrap.addEventListener('mousemove', e => {
      const r  = wrap.getBoundingClientRect();
      const dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2);
      const dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2);
      gsap.to(inner, {
        rotateY: dx * 14,
        rotateX: -dy * 10,
        duration: .25,
        ease: 'power2.out',
        transformPerspective: 700
      });
    });
    wrap.addEventListener('mouseleave', () => {
      gsap.to(inner, {
        rotateY: 0,
        rotateX: 0,
        duration: .6,
        ease: 'elastic.out(1,.5)',
        transformPerspective: 700
      });
    });
  });

})();
</script>
</body>
</html>