function getField(row, names) {
  for (const n of names) {
    if (row.hasOwnProperty(n) && row[n] !== null && row[n] !== undefined && String(row[n]).trim() !== "") {
      return String(row[n]).trim();
    }
  }
  for (const key of Object.keys(row)) {
    for (const n of names) {
      if (key.toLowerCase() === n.toLowerCase() && String(row[key]).trim() !== "") {
        return String(row[key]).trim();
      }
    }
  }
  return "";
}

function splitTanggalJam(raw) {
  if (!raw) return { tanggal: "", jam: "" };
  raw = raw.replace(/(^\s*")|("\s*$)/g, "").trim();
  const m = raw.match(/(\d{1,2}:\d{2}(?::\d{2})?)/);
  let jam = "", tanggal = raw;
  if (m) {
    jam = m[1];
    const parts = jam.split(":");
    if (parts.length >= 2) jam = parts[0].padStart(2,"0") + ":" + parts[1].padStart(2,"0");
    tanggal = raw.replace(m[0], "").replace(/,\s*$/, "").trim();
    tanggal = tanggal.replace(/(^"|"$)/g,"").trim();
  }
  return { tanggal, jam };
}

function renderTable(data) {
  const tbody = document.getElementById("scheduleBody");
  tbody.innerHTML = "";
  data.forEach((row, idx) => {
    let no = getField(row, ["No", "NO", "Nomor"]) || (idx + 1).toString();
    let keterangan = getField(row, ["Keterangan Sesi", "Keterangan"]) || getField(row, ["Kelas"]) || "-";
    let mata = getField(row, ["Mata Pelajaran"]);
    let tanggalField = getField(row, ["Tanggal"]);
    let jamField = getField(row, ["Jam"]);
    let durasi = getField(row, ["Durasi"]);
    let tanggal = tanggalField, jam = jamField;
    if (!jam) {
      const parts = splitTanggalJam(tanggalField);
      tanggal = parts.tanggal;
      jam = parts.jam;
    }
    const tr = document.createElement("tr");
    tr.className = "hover:bg-green-50";
    tr.innerHTML = `
      <td class="border px-4 py-2">${no}</td>
      <td class="border px-4 py-2">${keterangan}</td>
      <td class="border px-4 py-2">${mata}</td>
      <td class="border px-4 py-2">${tanggal}</td>
      <td class="border px-4 py-2">${jam}</td>
      <td class="border px-4 py-2">${durasi}</td>
    `;
    tbody.appendChild(tr);
  });
}

async function loadAndRenderCSV() {
  try {
    const resp = await fetch('schedule.csv', {cache: "no-store"});
    if (!resp.ok) throw new Error("Gagal memuat schedule.csv");
    const text = await resp.text();
    const parsed = Papa.parse(text, { header: true, skipEmptyLines: true });
    let data = parsed.data;
    if (!data || data.length === 0) {
      const parsed2 = Papa.parse(text, { header: false, skipEmptyLines: true });
      data = parsed2.data.map(row => ({
        "Keterangan Sesi": row[1] || row[0] || "",
        "Mata Pelajaran": row[2] || row[1] || "",
        "Tanggal": row[3] || row[2] || "",
        "Jam": row[4] || "",
        "Durasi": row[5] || ""
      }));
    }
    renderTable(data);
  } catch (err) {
    console.error(err);
    alert("Error: " + err.message);
  }
}
loadAndRenderCSV();