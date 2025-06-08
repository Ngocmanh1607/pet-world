import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

const userStore = defineStore('user', () => {
  const user = ref(null)

  const isLoggedIn = computed(() => Boolean(user.value))
  function setUser(u) {
    user.value = u
  }
  async function getUserFromLocal() {
    try {
      const localStorageUser = localStorage.getItem('user')
      if (localStorageUser) {
        const parsedUser = JSON.parse(localStorageUser)
        setUser(parsedUser)
      }
    } catch (error) {
      setUser(null)
      console.log(error)
    }
  }

  return {
    user,
    isLoggedIn,
    setUser,
    getUserFromLocal
  }
})

export default userStore
