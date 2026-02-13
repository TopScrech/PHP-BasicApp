<script>
    import {users} from './data-users.js'
    import {onMount} from "svelte";
    import User from './User.svelte'

    onMount(async() => {
        const url = 'http://localhost:8888/back-end/get-users.php'
        let res = await fetch(url)
        res = await res.json()
        console.log(res)
        let data = res.data
        $users = data
    })
</script>

<div id="users">
    {#each $users as user}
        <User {user}/>
    {/each}
</div>