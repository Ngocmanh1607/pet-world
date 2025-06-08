<template>
  <div class="not-found">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>The page you are looking for does not exist.</p>
    <router-link to="/" class="home-link">Go to Homepage</router-link>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
};
</script>

<style scoped>
.not-found {
  text-align: center;
  padding: 4rem 2rem;
}

h1 {
  font-size: 6rem;
  color: #4caf50;
  margin: 0;
}

h2 {
  font-size: 2rem;
  color: #333;
  margin: 1rem 0;
}

p {
  color: #666;
  margin-bottom: 2rem;
}

.home-link {
  display: inline-block;
  padding: 0.8rem 1.5rem;
  background-color: #4caf50;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.home-link:hover {
  background-color: #45a049;
}
</style>
