<script lang="ts">
    import {users} from "./data-users";

    export let user: { id: number, name: string, picture_name: string }
    const imgPath = 'http://localhost:8888/back-end/pictures/'
    let txtFile: HTMLInputElement

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

    const deleteUser = async() => {
        const deleteRoute = 'http://localhost:8888/back-end/remove-user.php?id=' + user.id

        const res = await fetch(deleteRoute)
        const payload = await res.json()

        if(!res.ok || !payload?.status) {
            return
        }

        $users = $users.filter(
            (item: { id: number }) => user.id != item.id
        )
    }

    const updateImage = async() => {
        const file = txtFile?.files?.[0]
        if(!file) return

        const updateRoute = 'http://localhost:8888/back-end/update-user-picture.php?id=' + user.id
        console.log(updateRoute)

        const data = new FormData()
        data.append('picture', file)

        const res = await fetch(updateRoute, {
            method: 'POST',
            body: data
        })

        let payload
        try {
            payload = await res.json()
        } catch {
            return
        }
        console.log(payload)

        if(!res.ok || !payload?.status || !payload?.picture_name) {
            return
        }

        user.picture_name = payload.picture_name
    }
</script>

<div class="user">
    <!--{user.name}-->
    <input type="text" bind:value={user.name} on:input={updateName}>

    <label for="picture{user.id}">
        <input type="file" bind:this={txtFile} name="picture{user.id}" id="picture{user.id}" on:change={updateImage}>

        <img src={`${imgPath}${user.picture_name}`} alt={user.name}>
    </label>

    <button on:click={deleteUser}>
        Delete
    </button>
</div>

<style>
    .user {
        display: grid;
        grid-template-columns: 100fr 20fr 20fr;
        padding-top: 20px;
    }
    .user img{
        width: 30px;
    }
    input{
        width: 100%;
        border: none;
        outline: none;
        color: white;
        font-size: inherit;
        background-color: transparent;
    }
    input[type="file"]{
        display: none;
    }
</style>
