<script lang="ts">
    import {users} from './data-users.js'
    import {onMount} from "svelte";
    import User from './User.svelte'

    onMount(async() => {
        const url = 'http://localhost:8888/back-end/get-users.php'
        const res = await fetch(url)
        const payload = await res.json()
        console.log(payload)
        $users = payload?.data ?? []
    })
</script>

<div id="users">
    {#each $users as user}
        <User {user}/>
    {/each}
</div>
