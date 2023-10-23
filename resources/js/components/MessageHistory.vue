<template>
    <div>
      <div class="message-history" v-if="selectedComplain">
        <div v-for="reply in selectedComplain.replies" :key="reply.id" class="message">
          <div class="message-content">{{ reply.body }}</div>
        </div>
      </div>
      <b-form-input v-model="messageContent" placeholder="Type your message here..." rows="5"></b-form-input>
      <div class="text-right mt-3">
        <b-button variant="primary" @click="sendMessage">Send</b-button>
        <b-button variant="secondary" @click="fetchReplies">Fetch Replies</b-button>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      selectedComplain: {
        type: Object,
        required: true
      },
      messageContent: {
        type: String,
        default: ""
      },
      fetchReplies: Function,
      sendMessage: Function
    }
  };
  </script>
