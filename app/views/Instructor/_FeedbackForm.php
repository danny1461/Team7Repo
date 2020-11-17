<div id="feedback-app">
	<div class="collapse show" id="formCollapse" ref="formCollapse">
		<form ref="form" method="POST" id="feedback-form" data-classid="<?php echo $class->classid?>" v-on:submit="submitForm($event)">
			<div class="form-row">
				<div class="form-group col-md-12 col-lg-4">
					<label for="feedback-title">Feedback Title</label>
					<input type="text" class="form-control" v-bind:class="errors.title ? 'is-invalid' : ''" id="feedback-title" name="feedbacktitle" placeholder="Midterm Feedback" v-model="title" required>
				</div>
				<div class="form-group col-md-6 col-lg-4">
					<label for="feedbackstart">Start Time</label>
					<input type="time" class="form-control" v-bind:class="errors.start ? 'is-invalid' : ''" id="feedbackstart" name="feedbackstart" v-model="start" required>
				</div>
				<div class="form-group col-md-6 col-lg-4">
					<label for="feedbackend">End Time</label>
					<input type="time" class="form-control" v-bind:class="errors.end ? 'is-invalid' : ''" id="feedbackend" name="feedbackend" v-bind:min="start" v-model="end" required>
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header">
					Fields for this feedback session:
				</div>
				<div class="card-body">
					<transition-group
						tag="div"
						v-bind:css="false"
						v-on:enter="animEnter"
						v-on:leave="animLeave"
					>
						<div v-for="(field, ndx) in fields" class="form-row field-row" v-bind:key="field.id" v-bind:class="ndx % 2 == 1 ? 'odd' : 'even'">
							<div class="form-group col-lg-5 col-md-6">
								<label class="question-label" v-bind:for="'title' + ndx">Question {{ ndx + 1 }} Label</label>
								<input type="text" class="form-control" v-bind:id="'title' + ndx" v-on:placeholder="'Question #' + (ndx + 1) +' Title'" v-model="field.label" required>
							</div>
							<div class="form-group col-lg-5 col-md-6">
								<label>Preview</label>
								<component
									v-bind:is="getFieldComponent(field.type)"
									v-bind:type="field.type"
									v-bind:options="field.options"
									v-on:add-option="addOption(field)"
									v-on:remove-option="removeOption(field, $event)"
									v-on:update-option="updateOption(field, $event)">
								</component>
							</div>
							<div class="form-group col-lg-2 col-md-12 border-left p-3">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" v-bind:id="'optional' + ndx" v-model="field.optional">
									<label class="form-check-label" v-bind:for="'optional' + ndx">Optional</label>
								</div>
								<button type="button" class="btn btn-danger w-100" v-on:click="removeField(ndx)"><i class="fas fa-trash"></i> Field</button>
							</div>
						</div>
					</transition-group>

					<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Add Field
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a v-for="(label, type) in fieldTypes" class="dropdown-item" href="javascript:void(0)" v-on:click="addField(type)">{{label}}</a>
					</div>
				</div>
			</div>

			<button type="submit" class="btn btn-primary float-right" v-bind:disabled="!formReady || processing">
				Create
				<i v-if="processing" class="fas fa-cog fa-spin"></i>
			</button>
		</form>
	</div>
	<div class="collapse" id="successCollapse" ref="successCollapse">
		<div class="alert alert-success text-center mb-5" role="alert">
			Feedback session created successfully!
		</div>

		<button type="button" class="btn btn-primary float-right" data-dismiss="modal">Close</button>
	</div>
</div>

<script type="text/javascript">
	// Inline code for applet (Vue.js 2.6)

	var fieldTypes = {
		SHORT_TEXT: 'Short Text',
		LONG_TEXT: 'Long Text',
		RADIO_GROUP: 'Radio Group',
		CHECKBOX_GROUP: 'Checkbox Group',
		RATING: 'Rating'
	};

	Vue.component('field-type-short-text', { template: `<input type="text" class="form-control" disabled>` });
	Vue.component('field-type-long-text', { template: `<textarea class="form-control" disabled></textarea>` });
	Vue.component('field-type-rating', {
		template: `
			<div class="rating-strip mt-2 text-center">
				<i v-for="i in 5" class="far fa-star"></i>
			</div>
		`
	});

	var inputGroupComp = {
		props: ['type', 'options'],

		template: `
			<div class="radio-group builder-option-group">
				<div v-for="(item, ndx) in options" class="input-group mb-1">
					<div class="input-group-prepend">
						<span class="input-group-text">
							<i v-if="type == 'CHECKBOX_GROUP'" class="far fa-square"></i>
							<i v-else class="far fa-circle"></i>
						</span>
					</div>
					<input type="text" class="form-control" v-model="item" v-bind:placeholder="'Option #' + (ndx + 1)" v-on:input="$emit('update-option', {ndx: ndx, val: $event.target.value})" required>
					<div class="input-group-append">
						<button type="button" class="btn btn-danger btn-delete" v-bind:disabled="options.length <= 2" v-on:click="$emit('remove-option', ndx)"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<button type="button" class="btn btn-primary" v-on:click="$emit('add-option')">Add Option</button>
			</div>
		`
	};

	Vue.component('field-type-radio-group', inputGroupComp);
	Vue.component('field-type-checkbox-group', inputGroupComp);

	docReady(function() {
		window.feedbackApp = new Vue({
			el: '#feedback-app',

			data: function() {
				return {
					title: '',
					start: '00:00',
					end: '00:00',
					
					fieldTypes: fieldTypes,
					fields: [],
					errors: {},

					processing: false,
					submitted: false,
					nextFieldId: 1
				};
			},

			mounted: function() {
				$([this.$refs.formCollapse, this.$refs.successCollapse]).collapse({
					toggle: false
				});

				let that = this;
				$('#textfeedback').on('hidden.bs.modal', function() {
					that.reset();
				});

				this.reset();
			},

			computed: {
				formReady: function() {
					if (!this.title.trim() || !this.start || !this.end || this.start == this.end || !this.fields.length) {
						return false;
					}

					return this.fields.reduce(function(carry, field) {
						if (!carry || !field.label) {
							return false;
						}

						if (field.options) {
							if (field.options.length < 2) {
								return false;
							}

							for (let i in field.options) {
								if (!field.options[i].trim()) {
									return false;
								}
							}
						}

						return carry;
					}, true);
				}
			},

			methods: {
				addField: function(type) {
					let field = {
						id: this.nextFieldId++,
						type: type,
						label: '',
						options: undefined,
						optional: false
					};

					if (type == 'RADIO_GROUP' || type == 'CHECKBOX_GROUP') {
						field.options = ['', ''];
					}

					this.fields.push(field);
				},

				removeField: function(ndx) {
					this.fields.splice(ndx, 1);
				},

				getFieldComponent: function(type) {
					return `field-type-${type.toLowerCase().replace(/_/g, '-')}`;
				},

				addOption: function(field) {
					if (!field.options) {
						field.options = [];
					}

					field.options.push('');
				},

				removeOption: function(field, ndx) {
					field.options.splice(ndx, 1);
				},

				updateOption: function(field, {ndx, val}) {
					this.$set(field.options, ndx, val);
				},

				reset: function() {
					this.title = '';
					this.start = '00:00';
					this.end = '00:00';
					this.fields = [];
					this.submitted = false;
				},

				submitForm: function(e) {
					e.preventDefault();
					if (!this.formReady) {
						return;
					}

					let data = {
						title: this.title,
						start: this.start,
						end: this.end,
						fields: JSON.stringify(this.fields.map(f => ({
							type: f.type,
							label: f.label,
							options: f.options,
							optional: f.optional
						})))
					};
					
					let classid = $(e.target).data("classid"),
						that = this;

					this.processing = true;
					$.post(`${BASEURL}Feedback/FeedbackForm/${classid}`, data, function(resp) {
						if (resp.errors) {
							that.$set(that, 'errors', resp.errors);
						}
						else if (resp.success) {
							// Yay!
							that.submitted = true;
						}

						// $('#textfeedback').modal('hide');
					}).always(function() {
						that.processing = false;
					});
				},

				animEnter: function(el, done) {
					let $el = $(el),
						realHeight = $(el).height();
					$el.height(0).addClass('transition-250');

					this.$nextTick(function() {
						$el.height(realHeight);
						setTimeout(function() {
							$el.height('auto');
							done();
						}, 250);
					});
				},

				animLeave: function(el, done) {
					let $el = $(el);
					$el.height($el.height()).addClass('transition-250');

					this.$nextTick(function() {
						$el.height(0);
						setTimeout(done, 250);
					});
				}
			},

			watch: {
				start: function() {
					if (this.start.localeCompare(this.end) > 0) {
						this.end = this.start;
					}
				},

				submitted: function() {
					$(this.$refs.formCollapse).collapse(this.submitted ? 'hide': 'show');
					$(this.$refs.successCollapse).collapse(this.submitted ? 'show': 'hide');
				}
			}
		});
	});
</script>