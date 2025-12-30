<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;

use App\Models\EmailTemplate;
use Illuminate\Support\Str;

class TemplateForm extends Component
{
    public $templateId;
    public $name;
    public $slug;
    public $subject;
    public $body_html;
    public $body_text;
    public $is_active = true;
    public $variables = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:email_templates,slug',
        'subject' => 'required|string|max:255',
        'body_html' => 'required|string',
        'is_active' => 'boolean',
    ];

    public function mount($templateId = null)
    {
        if ($templateId) {
            $this->templateId = $templateId;
            $template = EmailTemplate::findOrFail($templateId);
            $this->name = $template->name;
            $this->slug = $template->slug;
            $this->subject = $template->subject;
            $this->body_html = $template->body_html;
            $this->body_text = $template->body_text;
            $this->is_active = $template->is_active;
        }
    }

    public function updatedName($value)
    {
        if (!$this->templateId) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->templateId) {
            $rules['slug'] = 'required|string|max:255|unique:email_templates,slug,' . $this->templateId;
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'subject' => $this->subject,
            'body_html' => $this->body_html,
            'body_text' => strip_tags($this->body_html), // Auto-generate plain text
            'is_active' => $this->is_active,
            'variables' => ['donor_name', 'donor_email', 'amount', 'donation_date', 'reference', 'project_name', 'organization_name'],
        ];

        if ($this->templateId) {
            $template = EmailTemplate::find($this->templateId);
            $template->update($data);
            session()->flash('message', 'Template updated successfully.');
        } else {
            EmailTemplate::create($data);
            session()->flash('message', 'Template created successfully.');
        }

        return redirect()->route('admin.notifications.templates');
    }

    public function render()
    {
        return view('livewire.admin.notifications.template-form');
    }
}
