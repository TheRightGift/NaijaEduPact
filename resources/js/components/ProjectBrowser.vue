<template>
    <div>
        <div class="row">
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">search</i>
                <input type="text" id="search" v-model="searchTerm" @input="updateFilters">
                <label for="search">Search by project title...</label>
            </div>
            </div>

        <div v-if="loading" class="row center-align" style="padding-top: 50px;">
            <div class="col s12">
                <div class="preloader-wrapper big active">
                    <div class="spinner-layer spinner-blue-only">
                        <div class="circle-clipper left"><div class="circle"></div></div>
                        <div class="gap-patch"><div class="circle"></div></div>
                        <div class="circle-clipper right"><div class="circle"></div></div>
                    </div>
                </div>
            </div>            
        </div>

        <div v-else class="row">
            <div class="col s12 m6 l4" v-for="project in filteredProjects" :key="project.id">
                <div class="card">
                    <div class="card-image">
                        <img :src="getImageUrl(project.cover_image_path)" @error="setDefaultImage">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{ project.title }}<i class="material-icons right">more_vert</i></span>
                        <p><strong>{{ project.university.name }}</strong></p>
                        <br>
                        <div class="progress">
                            <div class="determinate" :style="{ width: getProgress(project) + '%' }"></div>
                        </div>
                        <p>₦{{ formatCurrency(project.current_amount) }} raised of ₦{{ formatCurrency(project.goal_amount) }}</p>
                    </div>
                    <div class="card-action">
                        <a :href="`/projects/${project.slug}`" class="indigo-text">View Project Details</a>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ project.title }}<i class="material-icons right">close</i></span>
                        <p>{{ project.description }}</p>
                    </div>
                </div>
            </div>
            
            <div v-if="!loading && filteredProjects.length === 0" class="col s12">
                 <div class="card-panel">
                    <p class="center-align">No projects match your search criteria.</p>
                 </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            projects: [],
            filteredProjects: [],
            searchTerm: '',
            loading: true,
            defaultImageUrl: 'https://via.placeholder.com/400x300.png?text=Project+Image'
        };
    },
    methods: {
        async fetchProjects() {
            this.loading = true;
            try {
                const response = await axios.get('/api/projects');
                this.projects = response.data;
                this.filteredProjects = this.projects; // Initially show all
            } catch (error) {
                console.error("There was an error fetching the projects:", error);
            } finally {
                this.loading = false;
            }
        },
        updateFilters() {
            const term = this.searchTerm.toLowerCase();
            this.filteredProjects = this.projects.filter(project =>
                project.title.toLowerCase().includes(term) ||
                project.university.name.toLowerCase().includes(term)
            );
        },
        getProgress(project) {
            if (project.goal_amount > 0) {
                let progress = (project.current_amount / project.goal_amount) * 100;
                return Math.min(progress, 100); // Cap at 100%
            }
            return 0;
        },
        formatCurrency(amount) {
            if (!amount) return '0.00';
            return Number(amount).toLocaleString('en-NG', { minimumFractionDigits: 2 });
        },
        getImageUrl(path) {
            // If path exists, return the full storage path, otherwise return default
            return path ? `${path}` : this.defaultImageUrl;
        },
        setDefaultImage(event) {
            // Fired if the project image fails to load (e.g., 404)
            event.target.src = this.defaultImageUrl;
        }
    },
    mounted() {
        console.log('here');
        
        this.fetchProjects();

        // Initialize Materialize card reveal (if not already done globally)
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.card');
            // This is a basic way; a more robust app would init this *after*
            // the component mounts and renders.
        });
    }
}
</script>