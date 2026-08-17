<script setup>
import { computed, ref } from "vue";


const version = "v1.0.0";


// ======================
// App
// ======================

const showInstallHelp = ref(false);


const isStandalone = computed(() => {
  return (
    window.matchMedia("(display-mode: standalone)").matches ||
    window.navigator.standalone === true
  );
});


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


// ======================
// Push Notifications
// ======================

const VAPID_PUBLIC_KEY =
  "BI5q0C-_08G1cwArfgnt-iIWn45useXd_eL_IVNvFNIqfDMtaDTObQBulsdrDGf1shD69LvCfc8XeSutzGxftnM";


const pushStatus = ref("");
const subscriptionJson = ref("");


function urlBase64ToUint8Array(base64String) {
  const padding =
    "=".repeat(
      (4 - base64String.length % 4) % 4
    );


  const base64 =
    (base64String + padding)
      .replace(/-/g, "+")
      .replace(/_/g, "/");


  const rawData =
    window.atob(base64);


  return Uint8Array.from(
    [...rawData].map(
      char => char.charCodeAt(0)
    )
  );
}


async function enablePush() {
  try {

    if (!("serviceWorker" in navigator)) {
      pushStatus.value =
        "Service Worker nicht verfügbar.";

      return;
    }


    if (!("PushManager" in window)) {
      pushStatus.value =
        "Push wird auf diesem Gerät nicht unterstützt.";

      return;
    }


    const permission =
      await Notification.requestPermission();


    if (permission !== "granted") {
      pushStatus.value =
        "Benachrichtigungen wurden nicht erlaubt.";

      return;
    }


    const registration =
      await navigator.serviceWorker.ready;


    let subscription =
      await registration.pushManager.getSubscription();


    if (!subscription) {

      subscription =
        await registration.pushManager.subscribe({
          userVisibleOnly: true,

          applicationServerKey:
            urlBase64ToUint8Array(
              VAPID_PUBLIC_KEY
            )
        });
    }


    subscriptionJson.value =
      JSON.stringify(
        subscription.toJSON(),
        null,
        2
      );


    console.log(
      "Push Subscription:",
      subscriptionJson.value
    );


    try {

      await navigator.clipboard.writeText(
        subscriptionJson.value
      );


      pushStatus.value =
        "Benachrichtigungen aktiviert ❤️ Subscription kopiert.";

    } catch (clipboardError) {

      console.warn(
        "Subscription konnte nicht automatisch kopiert werden:",
        clipboardError
      );


      pushStatus.value =
        "Benachrichtigungen aktiviert ❤️ Kopiere die Subscription unten.";
    }


  } catch (error) {

    console.error(
      "Push registration failed:",
      error
    );


    pushStatus.value =
      "Fehler beim Aktivieren der Benachrichtigungen.";
  }
}
</script>


<template>
  <div class="app">

    <div class="content">

      <h1 class="title">
        Für dich, Emmi ❤️
      </h1>


      <div
        class="heart-container"
        @click="nextMessage"
      >
        <div class="heart"></div>
      </div>


      <transition
        name="fade"
        mode="out-in"
      >
        <p
          class="message"
          :key="currentIndex"
        >
          {{ messages[currentIndex] }}
        </p>
      </transition>


      <p class="hint">
        Tippe auf das Herz 💖
      </p>


      <!-- PWA Installation -->

      <button
        v-if="!isStandalone"
        class="install-btn"
        @click="showInstallHelp = true"
      >
        App installieren ❤️
      </button>


      <!-- Push Notifications -->

      <button
        v-if="isStandalone"
        class="install-btn"
        @click="enablePush"
      >
        Nachrichten erlauben 💌
      </button>


      <p
        v-if="pushStatus"
        class="push-status"
      >
        {{ pushStatus }}
      </p>


      <!--
        Temporary setup helper.

        Once subscription.json has been copied
        to the local push-sender this can be removed.
      -->

      <textarea
        v-if="subscriptionJson"
        v-model="subscriptionJson"
        class="subscription-output"
        readonly
      ></textarea>


      <!-- Installation Modal -->

      <div
        v-if="showInstallHelp"
        class="modal"
      >
        <div class="modal-card">

          <h2>
            Auf iPhone installieren
          </h2>

          <p>
            1. Tippe unten in Safari auf Teilen.
          </p>

          <p>
            2. Wähle „Zum Home-Bildschirm“.
          </p>

          <p>
            3. Tippe auf „Hinzufügen“.
          </p>


          <button
            @click="showInstallHelp = false"
          >
            Verstanden
          </button>

        </div>
      </div>


      <p class="version">
        {{ version }}
      </p>

    </div>
  </div>
</template>


<style scoped>

.app {
  min-height: 100vh;
  width: 100vw;

  background:
    linear-gradient(
      135deg,
      #fd878b,
      #fcb7a4
    );

  display: flex;

  justify-content: center;
  align-items: center;

  text-align: center;

  font-family:
    system-ui,
    sans-serif;

  overflow-x: hidden;
}


.content {
  width: 100%;

  max-width: 500px;

  padding: 30px 20px;

  display: flex;

  flex-direction: column;

  align-items: center;

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

  color:
    rgba(
      255,
      255,
      255,
      0.8
    );
}


/* ======================
   Heart
   ====================== */

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

  transform:
    rotate(-45deg);

  animation:
    pulse 1.2s infinite;

  transition:
    transform 0.2s;
}


.heart-container:active .heart {
  transform:
    rotate(-45deg)
    scale(1.3);
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


/* ======================
   Heart Animation
   ====================== */

@keyframes pulse {

  0% {
    transform:
      rotate(-45deg)
      scale(1);
  }

  50% {
    transform:
      rotate(-45deg)
      scale(1.2);
  }

  100% {
    transform:
      rotate(-45deg)
      scale(1);
  }
}


/* ======================
   Text Fade
   ====================== */

.fade-enter-active,
.fade-leave-active {
  transition:
    opacity 0.4s,
    transform 0.4s;
}


.fade-enter-from {
  opacity: 0;

  transform:
    translateY(10px);
}


.fade-leave-to {
  opacity: 0;

  transform:
    translateY(-10px);
}


/* ======================
   Buttons
   ====================== */

.install-btn {
  margin-top: 24px;

  border: none;

  border-radius: 999px;

  padding: 12px 20px;

  font-size: 1rem;

  background: white;

  color: #e63970;

  font-weight: 700;

  cursor: pointer;
}


/* ======================
   Push
   ====================== */

.push-status {
  color: white;

  font-size: 0.85rem;

  margin: 0;

  max-width: 320px;
}


.subscription-output {
  width: 100%;

  max-width: 350px;

  min-height: 180px;

  box-sizing: border-box;

  border: none;

  border-radius: 12px;

  padding: 12px;

  font-family: monospace;

  font-size: 0.7rem;

  resize: vertical;
}


/* ======================
   Modal
   ====================== */

.modal {
  position: fixed;

  inset: 0;

  background:
    rgba(
      0,
      0,
      0,
      0.35
    );

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


/* ======================
   Version
   ====================== */

.version {
  color: #fff;

  font-size: 0.7rem;

  opacity: 0.6;
}


/* ======================
   Mobile
   ====================== */

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

</style>