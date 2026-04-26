<template>
  <div class="app">
    <div class="content">
      <h1 class="title">Für dich, Emmi ❤️</h1>

      <div class="heart-container" @click="nextMessage">
        <div class="heart"></div>
      </div>

      <transition name="fade" mode="out-in">
        <p class="message" :key="currentIndex">
          {{ messages[currentIndex] }}
        </p>
      </transition>

      <p class="hint">Tippe auf das Herz 💖</p>
      <button class="install-btn" @click="showInstallHelp = true">
        App installieren ❤️
      </button>

      <div v-if="showInstallHelp" class="modal">
        <div class="modal-card">
          <h2>Auf iPhone installieren</h2>
          <p>1. Tippe unten in Safari auf Teilen.</p>
          <p>2. Wähle „Zum Home-Bildschirm“.</p>
          <p>3. Tippe auf „Hinzufügen“.</p>

          <button @click="showInstallHelp = false">
            Verstanden
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const showInstallHelp = ref(false);

const messages = [
  "Ich liebe dich.",
  "Du machst mein Leben besser.",
  "Mit dir fühlt sich alles richtig an.",
  "Ich bin so froh, dich zu haben.",
  "Für mich bist du perfekt, Emmi.",
  "Du bist mein Sonnenschein an bewölkten Tagen.",
  "Jeder Moment mit dir ist kostbar.",
  "Ich schätze deine Liebe und Unterstützung so sehr.",
  "Du bist mein Ein und Alles, Emmi.",
  "Ich liebe dich mehr, als Worte ausdrücken können.",
  "Du bist mein Herz, Emmi.",
  "Ich liebe dich so so so sehr",
  "Du bist mein größtes Glück",
  "Du bist super süß, Emmi",
  "Ich liebe dich über alles, Emmi",
  "Du bist der tollste Mensch auf der Welt",
  "Ich bin so dankbar, dich zu haben, Emmi",
];

const currentIndex = ref(0);

function nextMessage() {
  currentIndex.value =
    (currentIndex.value + 1) % messages.length;
}
</script>

<style scoped>
.app {
  height: 100vh;
  width: 100vw;
  background: linear-gradient(135deg, #fd878b, #fcb7a4);
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  font-family: system-ui, sans-serif;
}

.content {
  display: flex;
  flex-direction: column;
  
  gap: 50px;
}

.title {
  font-size: 2rem;
  margin-bottom: 20px;
  color: white;
}

.message {
  font-size: 1.3rem;
  color: white;
  margin-top: 20px;
  min-height: 60px;
}

.hint {
  font-size: 0.9rem;
  margin-top: 15px;
  color: rgba(255,255,255,0.8);
}

/* Herz */
.heart-container {
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

.heart {
  width: 100px;
  height: 100px;
  background-color: red;
  position: relative;
  transform: rotate(-45deg);
  animation: pulse 1.2s infinite;
  transition: transform 0.2s;
}

.heart-container:active .heart {
  transform: rotate(-45deg) scale(1.3);
}

.heart::before,
.heart::after {
  content: "";
  width: 100px;
  height: 100px;
  background-color: red;
  border-radius: 50%;
  position: absolute;
}

.heart::before {
  top: -50px;
  left: 0;
}

.heart::after {
  left: 50px;
  top: 0;
}

/* Animation */
@keyframes pulse {
  0% { transform: rotate(-45deg) scale(1); }
  50% { transform: rotate(-45deg) scale(1.2); }
  100% { transform: rotate(-45deg) scale(1); }
}

/* Text Fade Animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s, transform 0.4s;
}

.fade-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Mobile */
@media (max-width: 480px) {
  .heart {
    width: 80px;
    height: 80px;
  }

  .heart::before,
  .heart::after {
    width: 80px;
    height: 80px;
  }

  .heart::before {
    top: -40px;
  }

  .heart::after {
    left: 40px;
  }

  .title {
    font-size: 1.6rem;
  }

  .message {
    font-size: 1.1rem;
  }
}
.install-btn {
  margin-top: 24px;
  border: none;
  border-radius: 999px;
  padding: 12px 20px;
  font-size: 1rem;
  background: white;
  color: #e63970;
  font-weight: 700;
}

.modal {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.35);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.modal-card {
  background: white;
  color: #333;
  border-radius: 20px;
  padding: 24px;
  max-width: 320px;
}
</style>