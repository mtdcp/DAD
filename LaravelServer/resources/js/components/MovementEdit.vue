<template>
  <form>
        <h2 class="text-center font-weight-bold text-monospace">Edit movement with ID {{ movementToEdit.id }}</h2> <br>
            <div class="form-group">
                <label for="inputCat">Category:</label><br>
                <select id="categories" name="categories" v-model="movementToEdit.category_id">
                    <option v-for="category in categoriesList"> {{ category }} </option>
                </select>
            </div>
            <label for="inputDescription">Description:</label>
            <input
                type="description" class="form-control" v-model="movementToEdit.description"
                name="description" id="description" value=""/><br>

            <div class="form-group">
                <a class="btn btn-secondary" v-on:click.prevent="saveMovement">Save</a>
                <a class="btn btn-light" v-on:click.prevent="cancel()">Cancel</a>
            </div>
    </form>
</template>

<script>
    export default {
        props: [
            'movementToEdit',
            'categoriesList'
        ],
        methods: {
            saveMovement: function() {
                this.$emit('save-movement'); 
            },
            cancel: function() {
                this.$emit('cancel-editing'); 
            },
            fillSpinner: function() {
                this.$emit('fill-spinner');     
            },
        },
        mounted() {
            this.fillSpinner();
        }
    }
</script>