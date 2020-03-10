<template>
    <v-app style="border: 1px solid dimgray; padding: 20px; border-radius: 5px; width: 90%;">
        <ValidationObserver ref="observer" v-slot="{ errors }">
            <form>
                <div class="text-center">
                    <v-dialog v-if="preview" v-model="dialogPhoto" width="500">
                        <template v-slot:activator="{ on }">
                            <v-btn style="position: absolute; top: 0; left: 150px" class="mx-2" color="red" dark fab
                                   x-small @click="deletePhoto">
                                <v-icon dark center>mdi-delete</v-icon>
                            </v-btn>
                            <v-img dark v-on="on" :src="preview" aspect-ratio="2" contain
                                   style="width: 150px; height: 150px"/>
                        </template>
                        <v-card>
                            <v-card-title class="headline grey lighten-2" primary-title>Photo</v-card-title>
                            <v-card-text>
                                <v-img :src="preview" aspect-ratio="2" contain style="margin-top: 20px"></v-img>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-col cols="12" style="text-align: center">
                                    <v-btn color="primary" @click="dialogPhoto = false">Close</v-btn>
                                </v-col>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
                <ValidationProvider v-slot="{ errors }" name="Photo"
                                    rules="size:5000|ext:jpeg,jpg,png|dimensions:300,300">
                    <v-file-input
                        id="file"
                        v-model="file"
                        :label="fileLabel"
                        data-vv-validate-on="change"
                        accept="image/png, image/jpeg"
                        show-size
                        :error-messages="errors"
                        @change="handleChange"
                        @click:clear="clear()"
                        solo
                    ></v-file-input>
                </ValidationProvider>
                <v-overlay :value="overlay">
                    <v-progress-circular indeterminate size="64"></v-progress-circular>
                </v-overlay>

                <ValidationProvider v-slot="{ errors }" name="Name" rules="required|max:256">
                    <v-label>Name*</v-label>
                    <v-text-field
                        v-model="employee.name"
                        :counter="256"
                        :error-messages="errors"
                        placeholder="Name"
                        required
                        solo
                    ></v-text-field>
                </ValidationProvider>
                <ValidationProvider v-slot="{ errors }" name="Phone" rules="required|customPhone">
                    <v-label>Phone*</v-label>
                    <v-text-field
                        id="phone"
                        v-model="employee.phone"
                        :error-messages="errors"
                        placeholder="Phone"
                        required
                        solo
                    ></v-text-field>
                </ValidationProvider>
                <ValidationProvider v-slot="{ errors }" name="Email" rules="required|email">
                    <v-label>E-mail*</v-label>
                    <v-text-field
                        v-model="employee.email"
                        :error-messages="errors"
                        placeholder="E-mail"
                        required
                        solo
                    ></v-text-field>
                </ValidationProvider>
                <ValidationProvider v-slot="{ errors }" name="Position" rules="required">
                    <v-label>Position</v-label>
                    <v-autocomplete
                        :items="positions"
                        item-text="name"
                        item-value="id"
                        v-model="employee.position_id"
                        :error-messages="errors"
                        :clearable="true"
                        solo
                    ></v-autocomplete>
                </ValidationProvider>

                <ValidationProvider v-slot="{ errors }" name="Salary" rules="required|max_value:500000">
                    <v-label>Salary</v-label>
                    <v-text-field
                        id="salary"
                        v-model="employee.salary"
                        :error-messages="errors"
                        solo
                    ></v-text-field>
                </ValidationProvider>

                <v-label>Head</v-label>
                <v-autocomplete
                    :items="parents"
                    solo
                    item-text="name"
                    item-value="id"
                    v-model="employee.parent_id"
                    placeholder="Head"
                    :clearable="true"
                ></v-autocomplete>
                <v-col cols="12">
                    <v-menu v-model="menu" :close-on-content-click="false" max-width="290">
                        <template v-slot:activator="{ on }">
                            <v-label>Date of employment</v-label>
                            <v-text-field
                                :value="employmentAt"
                                clearable
                                readonly
                                v-on="on"
                                solo
                                @click:clear="date = null"
                            ></v-text-field>
                        </template>
                        <v-date-picker no-title v-model="date" @change="menu = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>

                <v-col v-if="employee.id" class="d-flex justify-space-around mb-12">
                    <v-col class="pa-12">
                        <v-col cols="12">
                            <span>Created at:</span>
                            <span>{{ formatDate(employee.created_at) }}</span>
                        </v-col>
                        <v-col cols="12">
                            <span>Updated at:</span>
                            <span>{{ formatDate(employee.updated_at) }}</span>
                        </v-col>
                    </v-col>
                    <v-col class="pa-12">
                        <v-col cols="12">
                            <span>Admin created id:</span>
                            <span>{{ employee.admin_created_id }}</span>
                        </v-col>
                        <v-col cols="12">
                            <span>Admin updated id:</span>
                            <span>{{ employee.admin_updated_id }}</span>
                        </v-col>
                    </v-col>
                </v-col>
                <v-col cols="12" style="text-align: right; margin: 20px">
                    <v-btn class="ma-2" tile color="dimgray" outlined @click="cancel">Cancel</v-btn>
                    <v-btn class="ma-2" tile color="dimgray" dark @click="save">Save</v-btn>
                </v-col>
            </form>
        </ValidationObserver>
    </v-app>

</template>

<script>
    import {required, email, max, size, dimensions, ext} from 'vee-validate/dist/rules'
    import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
    import Inputmask from 'inputmask'
    import moment from 'moment'
    import axios from 'axios'

    setInteractionMode('eager')
    extend('required', {
        ...required,
        message: '{_field_} can not be empty',
    })
    extend('max', {
        ...max,
        message: '{_field_} may not be greater than {length} characters',
    })
    extend('email', {
        ...email,
        message: 'Email must be valid',
    })
    extend('max_value', {
        message: 'Maximum 500,000',
        validate: value => {
            if (!value || parseInt(value.toString().replace(/[^0-9.]/g, "")) > 500000) {
                return false;
            }
            return true;
        }
    })
    extend('customPhone', {
        message: 'Required format +380(xx) XXX XX XX',

        validate: (value) => {

            if (!value || value.replace(/[^0-9.]/g, "").toString().length < 12) {
                return false;
            }
            return true;
        }
    })
    extend('size', {
        ...size,
        message: 'Format file jpg,png Up 5MB, the minimum size of 300x300px ',
    })
    extend('dimensions', {
        ...dimensions,
        message: 'Format file jpg,png Up 5MB, the minimum size of 300x300px ',

        validate: (value, res) => {
            if (document.getElementById('file').files[0]) {
                var _URL = window.URL || window.webkitURL;
                var file = document.getElementById('file').files[0];
                var img = new Image();
                img.onload = function () {
                    if (this.width < res.width || this.height < res.height) {
                        return false;
                    }
                };
                img.src = _URL.createObjectURL(file);
            } else {
                return true;
            }
        }
    })
    extend('ext', {
        ...ext,
        message: 'Format file jpg,png Up 5MB, the minimum size of 300x300px ',
    })
    export default {
        name: 'FormEmployee',
        components: {ValidationProvider, ValidationObserver},
        props: {
            employee: {
                type: Object,
            },
            positions: {
                type: Array,
            },
            parents: {
                type: Array,
            },
        },
        data() {
            return {
                date: this.employee['employment_at'] || '',
                menu: false,
                dialogPhoto: false,
                action: '/admin/employees/edit/' + this.employee.id,
                file: null,
                preview: this.employee.photo || null,
                overlay: false,
            }
        },
        computed: {
            employmentAt() {
                this.employee['employment_at'] = this.date;
                return this.date ? moment(this.date).format('DD.MM.YY') : null
            },
            fileLabel() {
                if (this.employee.id) {
                    return "Upload photo"
                }
                return "Browse";
            }
        },
        mounted() {
            new Inputmask("+999(99) 999 99 99").mask(document.getElementById('phone'));
            new Inputmask('decimal', {
                'alias': 'numeric',
                'groupSeparator': ',',
                'digitsOptional': true,
                'allowMinus': false,
                'rightAlign': false,
                'unmaskAsNumber': true
            }).mask(document.getElementById('salary'));
        },
        methods: {
            async save() {
                const isValid = await this.$refs.observer.validate()
                if (isValid) {
                    this.overlay = true;
                    axios.post(this.action, this.constructData(), {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        console.log('response', response)
                        this.overlay = false;
                        // window.location.href = '/admin/employees/'
                    }).catch(error => {
                        let errors = error.response.data.errors;
                        // this.$validator.errors.add({field: 'salary', msg: 'nope'})
                        // for (let key in errors){
                        //     // this.$refs.observer.errors[this.capitalize(key)] = errors[key];
                        // }
                        this.overlay = false;
                    })
                }
            },
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            cancel() {
                window.location.href = '/admin/employees/'
            },
            constructData() {
                let model = JSON.parse(JSON.stringify(this.employee));
                if (model.salary) {
                    model.salary = parseInt(model.salary.toString().replace(/[^0-9.]/g, ""));
                }
                model.parent_id = parseInt(model.parent_id) || '';

                let formData = new FormData();
                for (let key in model) {
                    formData.append(key, model[key]);
                }

                if (typeof this.file) {
                    formData.append('file', this.file);
                }

                return formData;
            },
            handleChange() {
                if (this.file)
                    this.preview = URL.createObjectURL(this.file);
            },
            clear() {
                this.preview = null;
            },
            formatDate(date) {
                return moment(date).format('DD.MM.YY')
            },
            deletePhoto() {
                this.file = null;
                this.preview = null;
                this.employee.photo = null;
            }
        },
    }
</script>

<style scoped>
    .theme--light.v-application {
        background: none;
    }
</style>
