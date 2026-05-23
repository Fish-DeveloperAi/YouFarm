<?php require 'layouts/header.php'; ?>

<div class="dashboard-wrap">

  <!-- Top greeting bar -->
  <div class="dash-header">
    <div>
      <h1 class="dash-title">Farm Dashboard</h1>
      <p class="dash-sub">Welcome back, <?= htmlspecialchars($_SESSION['fname'] ?? 'Farmer') ?> — live data for your land</p>
    </div>
    <div class="live-badge"><div class="pulse"></div> LIVE</div>
  </div>

  <!-- Alert banner (shown/hidden by JS) -->
  <div class="alert-banner" id="alert-banner" style="display:none">
    <span class="alert-icon">⚠️</span>
    <span id="alert-text">Loading alerts...</span>
  </div>

  <!-- Stat cards row -->
  <div class="stat-cards" id="stat-cards">
    <div class="stat-card" id="card-temp">
      <div class="stat-icon">🌡️</div>
      <div class="stat-body">
        <div class="stat-label">Temperature</div>
        <div class="stat-value" id="temp">--°C</div>
        <div class="stat-sub" id="feels-like">Feels like --°</div>
      </div>
    </div>
    <div class="stat-card" id="card-humidity">
      <div class="stat-icon">💧</div>
      <div class="stat-body">
        <div class="stat-label">Humidity</div>
        <div class="stat-value" id="humidity">--%</div>
        <div class="stat-sub" id="humidity-tip">--</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">💨</div>
      <div class="stat-body">
        <div class="stat-label">Wind</div>
        <div class="stat-value" id="wind">-- km/h</div>
        <div class="stat-sub" id="wind-dir">Direction: --</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">👁️</div>
      <div class="stat-body">
        <div class="stat-label">Visibility</div>
        <div class="stat-value" id="visibility">-- km</div>
        <div class="stat-sub" id="condition">--</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">📊</div>
      <div class="stat-body">
        <div class="stat-label">Pressure</div>
        <div class="stat-value" id="pressure">-- hPa</div>
        <div class="stat-sub" id="pressure-tip">--</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">🌅</div>
      <div class="stat-body">
        <div class="stat-label">Sunrise / Sunset</div>
        <div class="stat-value" id="sunrise">--:--</div>
        <div class="stat-sub" id="sunset">Sunset --:--</div>
      </div>
    </div>
  </div>

  <!-- Two-column middle row: weather card + crop advice -->
  <div class="mid-row">

    <div class="weather-card">
      <div class="wc-city" id="city-name">Loading...</div>
      <div class="wc-date" id="current-date">...</div>
      <div class="wc-main">
        <div>
          <span class="wc-temp" id="temp-big">--°</span>
          <div class="wc-cond" id="condition-big">...</div>
        </div>
        <div class="wc-icon" id="icon">🌤️</div>
      </div>
      <div class="wc-rain">
        <span>🌧️ Rain chance (next 3h):</span>
        <span id="rain-chance">--%</span>
      </div>
    </div>

    <div class="advice-card">
      <h3 class="advice-title">🌱 Crop Advisory</h3>
      <div id="advice-list" class="advice-list">
        <div class="advice-item skeleton">Loading advice...</div>
      </div>
    </div>

  </div>

  <!-- 5-day forecast strip -->
  <div class="forecast-card">
    <h3 class="section-title">5-Day Forecast</h3>
    <div class="forecast-strip" id="forecast-strip">
      <?php for($i=0;$i<5;$i++): ?>
      <div class="forecast-day skeleton">
        <div class="fc-day">--</div>
        <div class="fc-icon">--</div>
        <div class="fc-temp">--°</div>
        <div class="fc-rain">--%</div>
      </div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- Charts row -->
  <div class="charts-row">
    <div class="chart-card" style="flex:2">
      <h3 class="section-title">Temperature &amp; Humidity — Next 24h</h3>
      <div style="position:relative;height:260px">
        <canvas id="forecastChart" role="img" aria-label="Temperature and humidity forecast for next 24 hours">Loading...</canvas>
      </div>
    </div>
    <div class="chart-card" style="flex:1">
      <h3 class="section-title">Rain Probability</h3>
      <div style="position:relative;height:260px">
        <canvas id="rainChart" role="img" aria-label="Rain probability chart">Loading...</canvas>
      </div>
    </div>
  </div>

</div><!-- /dashboard-wrap -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const API_KEY = '6b097ed3c10b62524be68e2bec132c3a';
const CITY    = 'Casablanca';
const UNITS   = 'metric';

// ── Helpers ──────────────────────────────────────────────────────────────
function windDir(deg) {
  const dirs = ['N','NE','E','SE','S','SW','W','NW'];
  return dirs[Math.round(deg / 45) % 8];
}
function fmtTime(unix, tz) {
  return new Date((unix + tz) * 1000).toUTCString().slice(17,22);
}
function weatherEmoji(icon) {
  const map = {'01d':'☀️','01n':'🌙','02d':'⛅','02n':'🌙','03d':'☁️','03n':'☁️',
               '04d':'☁️','04n':'☁️','09d':'🌧️','09n':'🌧️','10d':'🌦️','10n':'🌧️',
               '11d':'⛈️','11n':'⛈️','13d':'❄️','13n':'❄️','50d':'🌫️','50n':'🌫️'};
  return map[icon] || '🌤️';
}

// ── Crop advice engine ────────────────────────────────────────────────────
function buildAdvice(current, forecast) {
  const tips = [];
  const temp  = current.main.temp;
  const hum   = current.main.humidity;
  const wind  = current.wind.speed * 3.6;
  const rain  = forecast.list[0]?.rain?.['3h'] || 0;

  if (temp > 35)  tips.push({ icon:'🔥', text:'Extreme heat — irrigate early morning or evening to reduce evaporation.' });
  if (temp < 5)   tips.push({ icon:'❄️', text:'Frost risk tonight — cover sensitive seedlings and check irrigation lines.' });
  if (hum > 80)   tips.push({ icon:'🍄', text:'High humidity — high fungal disease risk. Check for blight and mildew.' });
  if (hum < 30)   tips.push({ icon:'🏜️', text:'Very dry air — increase irrigation frequency, especially for shallow-rooted crops.' });
  if (wind > 40)  tips.push({ icon:'💨', text:'Strong winds — delay spraying. Check staking on young plants.' });
  if (rain > 5)   tips.push({ icon:'🌧️', text:'Heavy rain expected — delay fertiliser application to prevent run-off.' });

  // Positive tips
  if (temp >= 18 && temp <= 28 && hum >= 40 && hum <= 70)
    tips.push({ icon:'✅', text:'Ideal growing conditions right now. Good window for transplanting and foliar sprays.' });
  if (temp > 10 && temp < 20 && hum < 60)
    tips.push({ icon:'🌿', text:'Great conditions for leafy greens and brassicas today.' });

  // Rain-based irrigation tip
  const totalRain = forecast.list.slice(0,8).reduce((s,i) => s + (i.rain?.['3h'] || 0), 0);
  if (totalRain > 10) tips.push({ icon:'💧', text:`${totalRain.toFixed(1)}mm rain forecast today — skip irrigation and save water.` });
  else if (totalRain < 2 && hum < 50) tips.push({ icon:'💧', text:'Dry day ahead — plan your irrigation schedule for this afternoon.' });

  if (!tips.length) tips.push({ icon:'🌱', text:'Conditions are stable. Monitor crops normally.' });
  return tips;
}

// ── Main fetch ────────────────────────────────────────────────────────────
async function loadDashboard() {
  try {
    const [wRes, fRes] = await Promise.all([
      fetch(`https://api.openweathermap.org/data/2.5/weather?q=${CITY}&units=${UNITS}&appid=${API_KEY}`),
      fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${CITY}&units=${UNITS}&cnt=40&appid=${API_KEY}`)
    ]);
    if (!wRes.ok || !fRes.ok) throw new Error('API error');
    const w = await wRes.json();
    const f = await fRes.json();

    // ── Stat cards ──────────────────────────────────────────────────────
    document.getElementById('temp').textContent        = Math.round(w.main.temp) + '°C';
    document.getElementById('feels-like').textContent  = 'Feels like ' + Math.round(w.main.feels_like) + '°';
    document.getElementById('humidity').textContent    = w.main.humidity + '%';
    document.getElementById('humidity-tip').textContent = w.main.humidity > 70 ? 'High — watch for disease' : w.main.humidity < 35 ? 'Low — increase irrigation' : 'Normal';
    document.getElementById('wind').textContent        = (w.wind.speed * 3.6).toFixed(1) + ' km/h';
    document.getElementById('wind-dir').textContent   = 'Direction: ' + windDir(w.wind.deg);
    document.getElementById('visibility').textContent = (w.visibility / 1000).toFixed(1) + ' km';
    document.getElementById('condition').textContent  = w.weather[0].description;
    document.getElementById('pressure').textContent   = w.main.pressure + ' hPa';
    document.getElementById('pressure-tip').textContent = w.main.pressure > 1015 ? 'High — stable weather' : w.main.pressure < 1000 ? 'Low — rain likely' : 'Normal';
    document.getElementById('sunrise').textContent    = fmtTime(w.sys.sunrise, w.timezone);
    document.getElementById('sunset').textContent     = 'Sunset ' + fmtTime(w.sys.sunset, w.timezone);

    // ── Weather card ────────────────────────────────────────────────────
    document.getElementById('city-name').textContent    = w.name + ', ' + w.sys.country;
    document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {weekday:'long', month:'short', day:'numeric'});
    document.getElementById('temp-big').textContent     = Math.round(w.main.temp) + '°';
    document.getElementById('condition-big').textContent = w.weather[0].description;
    document.getElementById('icon').textContent         = weatherEmoji(w.weather[0].icon);
    const pop3h = f.list[0]?.pop ?? 0;
    document.getElementById('rain-chance').textContent  = Math.round(pop3h * 100) + '%';

    // ── Alert banner ────────────────────────────────────────────────────
    const alerts = [];
    if (w.main.temp > 35) alerts.push('Extreme heat warning — temperature above 35°C');
    if (w.main.temp < 3)  alerts.push('Frost alert — temperature near freezing');
    if ((w.wind.speed * 3.6) > 50) alerts.push('High wind warning — above 50 km/h');
    if (alerts.length) {
      document.getElementById('alert-text').textContent = alerts.join(' · ');
      document.getElementById('alert-banner').style.display = 'flex';
    }

    // ── Crop advice ─────────────────────────────────────────────────────
    const tips = buildAdvice(w, f);
    const list = document.getElementById('advice-list');
    list.innerHTML = tips.map(t =>
      `<div class="advice-item"><span class="advice-icon">${t.icon}</span><span>${t.text}</span></div>`
    ).join('');

    // ── 5-day forecast strip ─────────────────────────────────────────────
    // One entry per day (noon reading)
    const days = {};
    f.list.forEach(item => {
      const d = new Date(item.dt * 1000);
      const key = d.toDateString();
      const hour = d.getUTCHours() + (w.timezone / 3600);
      if (!days[key] || Math.abs(hour - 13) < Math.abs((days[key]._hour||0) - 13)) {
        item._hour = hour;
        days[key] = item;
      }
    });
    const dayList = Object.values(days).slice(0, 5);
    const strip = document.getElementById('forecast-strip');
    strip.innerHTML = dayList.map(item => {
      const d = new Date(item.dt * 1000);
      const dayName = d.toLocaleDateString('en-US', {weekday:'short'});
      return `<div class="forecast-day">
        <div class="fc-day">${dayName}</div>
        <div class="fc-icon">${weatherEmoji(item.weather[0].icon)}</div>
        <div class="fc-temp">${Math.round(item.main.temp)}°C</div>
        <div class="fc-rain">💧 ${Math.round((item.pop||0)*100)}%</div>
      </div>`;
    }).join('');

    // ── Charts — next 24h (8 x 3h readings) ─────────────────────────────
    const next8 = f.list.slice(0, 8);
    const labels  = next8.map(i => new Date(i.dt*1000).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',hour12:false}));
    const temps   = next8.map(i => Math.round(i.main.temp));
    const hums    = next8.map(i => i.main.humidity);
    const rains   = next8.map(i => Math.round((i.pop||0)*100));

    new Chart(document.getElementById('forecastChart'), {
      type: 'line',
      data: {
        labels,
        datasets: [
          { label:'Temp (°C)', data: temps, borderColor:'#eab308', backgroundColor:'rgba(234,179,8,0.08)', fill:true, tension:0.4, yAxisID:'y' },
          { label:'Humidity (%)', data: hums, borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.08)', fill:true, tension:0.4, yAxisID:'y1' }
        ]
      },
      options: {
        responsive:true, maintainAspectRatio:false,
        interaction:{ mode:'index', intersect:false },
        plugins:{ legend:{ labels:{ color:'#94a3b8' } } },
        scales:{
          y:  { position:'left',  grid:{color:'rgba(255,255,255,0.05)'}, ticks:{color:'#94a3b8'}, title:{display:true,text:'°C',color:'#eab308'} },
          y1: { position:'right', grid:{drawOnChartArea:false}, ticks:{color:'#94a3b8'}, title:{display:true,text:'%',color:'#16a34a'}, min:0, max:100 },
          x:  { grid:{display:false}, ticks:{color:'#94a3b8'} }
        }
      }
    });

    new Chart(document.getElementById('rainChart'), {
      type: 'bar',
      data: {
        labels,
        datasets: [{ label:'Rain chance (%)', data: rains, backgroundColor:'rgba(59,130,246,0.5)', borderColor:'#3b82f6', borderWidth:1, borderRadius:4 }]
      },
      options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{ labels:{ color:'#94a3b8' } } },
        scales:{
          y:{ grid:{color:'rgba(255,255,255,0.05)'}, ticks:{color:'#94a3b8'}, min:0, max:100 },
          x:{ grid:{display:false}, ticks:{color:'#94a3b8', maxRotation:45} }
        }
      }
    });

  } catch(e) {
    console.error('Dashboard error:', e);
    document.getElementById('city-name').textContent = 'Could not load weather data';
  }
}

loadDashboard();
</script>
<?php require 'layouts/footer.php'; ?>
