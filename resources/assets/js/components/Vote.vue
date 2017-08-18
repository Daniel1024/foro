<template>
    <div>
        <form>
            <button @click.prevent="addVote(1)"
                    :class="currentVote == 1 ? 'btn-primary' : 'btn-default'"
                    :disabled="voteInProgress"
                    class="btn">+1</button>
            Puntuación actual: <strong class="current-score">{{ currentScore }}</strong>
            <button @click.prevent="addVote(-1)"
                    :class="currentVote == -1 ? 'btn-primary' : 'btn-default'"
                    :disabled="voteInProgress"
                    class="btn">-1</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['score', 'vote', 'id', 'module'],
        data() {
            return {
                currentVote: this.vote ? parseInt(this.vote) : null,
                currentScore: parseInt(this.score),
                voteInProgress: false
            }
        },
        methods: {
            addVote(amount) {
                this.voteInProgress = true;

                if (this.currentVote === amount) {
                    this.processRequest('delete', 'vote');

                    this.currentVote = null;
                } else {
                    this.processRequest('post', amount);

                    this.currentVote = amount;
                }
            },
            processRequest(method, action) {
                window.axios[method](this.buildUrl(action)).then((response) => {
                    this.currentScore = response.data.new_score;

                    this.voteInProgress = false;
                }).catch((thrown) => {
                    alert('Ocurrió un error! '+ thrown);

                    this.voteInProgress = false;
                });
            },
            buildUrl(action) {
                if (action === 1) {
                    action = 'upvote';
                } else if (action === -1) {
                    action = 'downvote';
                }
                return '/' + this.module + '/'+ this.id + '/' + action;
            }
        }

    }
</script>
