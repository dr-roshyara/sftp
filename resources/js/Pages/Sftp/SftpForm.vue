<template>
     <Head title="SFTP File Transfer " />
            <div class="container mx-auto font-family:ui-serif,Georgia, Cambria justify-center mt-4" >
                   <form @submit.prevent="submit" class="flex flex-col justify-center mx-auto mb-2 md:w-1/2 "  >
                        <h1 class="text-2xl font-bold text-gray-900 p-2 mb-4 text-center"> 
                           Upload the file below 

                        </h1>
                              <!-- Start of success message  -->
                          <div v-if= "this.form.submitted" class="py-4 mb-4 text-red-700 font-extrabold">  
                                  {{$page.props.flash.message}}
                          </div>
                               <!-- start of icon  -->
                            <div v-if="form.processing" class="flex flex-wrap items-center space-x-3">
                                  <p class="text-red-600 py-2 font-bold" > 
                                          Datei wird versendet !
                                  </p>
                                  <div class="p-2 my-2"> 
                                  <!-- {{downloadIcon}}  -->
                                  <!-- <img :src="downloadIcon" />  -->
                                      <img :src="downloadIcon" 
                                      alt="HTML5 Icon" width="250" height="250">
                                  </div> 
                            </div>
                        <!-- anzahlung_rechnung  -->     
                        <div class="flex flex-col  py-2 mb-1">
                            <!-- <label class="text-gray-900 font-semibold block my-3 text-lg  w-1/3" 
                            for="sftp_file">Anzahlung Rechnung</label> -->
                            <input class=" w-2/3 bg-gray-100 px-4 py-2 rounded-lg focus:outline-none  text-center
                                    transition
                                    ease-in-out
                                    m-0" 
                                type="file"
                                @input ="form.sftp_file=$event.target.files[0]" 
                                step="any"
                                name="sftp_file" 
                                id="sftp_file" 
                                /> 
                            <!-- progress bar  -->
                        
                        
                        </div>
                        
                                         
                <!-- Speichern  -->
                 	<button type="submit" 
                     class="w-full mt-6 bg-indigo-600 rounded-lg px-4 py-2 text-lg text-white 
                        tracking-wide font-semibold font-sans mb-4">Save</button>
				
                    </form>
            </div> 
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
export default {
  props:{
        message: String,
        downloadIcon:String,
     },
    components:{
        Head,
       Link
    },
    data(){
        return {
           form: this.$inertia.form({
                    _method: 'PUT',
                     sftp_file:null,
                     processing: false,
                     submitted: false
                }),
               
        }
    },
  
  methods:{
      submit (){
          this.form.processing=true;          
          this.form.post(route('sftp.store'), {
                    // errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    // resetOnSuccess: false,
                     onSuccess: (response) => (this.showResponse (response)), 
                   
                },
                {
                    
                });
      },
      showResponse(response){  
        this.form.processing =false;
        this.form.submitted  =true;
       
  
      }
  }
}
</script>

<style>

</style>