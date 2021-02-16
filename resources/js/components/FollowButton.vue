<template>
    <div>
        <button class="btn btn-primary ml-4" @click="followUser" v-text="buttonText"></button>
    </div>
</template>

<script>
    export default {  
        mounted() {
            console.log('Component mounted.')
        },
        
        // props means properties that we are adding on the HTML file
        // With this we are saving the user id when we hit the Follow button
        props: ['userId', 'follows'],

        data: function() {
            return {
                status: this.follows,
            }
        },
        
        methods: {
            followUser() {
                axios.post('/follow/' + this.userId)
                .then(response => {
                    this.status = ! this.status;
                    console.log(response.data);
                })
                .catch(errors => {
                    if (errors.response.status == 401) {
                        window.location = '/login';
                    }
                });
            }
        },

        computed: {
            buttonText() {
                return (this.status) ? 'Unfollow' : 'Follow';
            }
        }
    }
</script>
