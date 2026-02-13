<script>
    import {users} from './data-users.js'
    export let user = {}

    const updateName = async() => {
        const updateRoute = 'http://localhost:8888/back-end/update-user-name.php?id=' + user.id
        console.log(updateRoute)

        const data = new FormData()
        data.append('name', user.name)
        console.log(user.name)

        let res = await fetch(updateRoute, {
            method: 'POST',
            body: data
        })

        console.log(res)
    }
</script>

<div class="user">
    <!--{user.name}-->
    <input type="text" bind:value={user.name} on:input={updateName}>
</div>