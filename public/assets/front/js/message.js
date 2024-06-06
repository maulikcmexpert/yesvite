import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import {
    getDatabase,
    ref,
    child,
    get,
} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";
const firebaseConfig = {
    apiKey: "AIzaSyAVgJQewYO8h1-_z2mrjaATCqJ4NH8eeCI",
    authDomain: "yesvite-976cd.firebaseapp.com",
    databaseURL: "https://yesvite-976cd-default-rtdb.firebaseio.com",
    projectId: "yesvite-976cd",
    storageBucket: "yesvite-976cd.appspot.com",
    messagingSenderId: "273430667581",
    appId: "1:273430667581:web:d5cc6f6c1cc9829de9e554",
    measurementId: "G-99SYL4VLEF",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
console.log(ref(database, "Messages"));
async function getMessages(userId1, userId2) {
    const messagesRef = ref(database, "Messages");

    try {
        const snapshot = await get(messagesRef);
        const messages = snapshot.val();

        const result = [];

        for (const key in messages) {
            if (
                messages[key].users &&
                messages[key].users.includes(userId1) &&
                messages[key].users.includes(userId2)
            ) {
                result.push({ key: key, ...messages[key] });
            }
        }

        return result;
    } catch (error) {
        console.error("Error retrieving messages:", error);
    }
}

// Usage
getMessages("198", "204").then((messages) => {
    console.log("Messages:", messages);
});
