<script>
    import {users} from './data-users.js'
    export let user = {}
    const imgPath = 'http://localhost:8888/back-end/pictures/'

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

    <label for="picture{user.id}">
        <img src={imgPath}{user.picture_name}>
    </label>
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
    button{
        outline: none;
        border: none;
        background-color: transparent;
    }
    input[type="file"]{
        display: none;
    }
</style>