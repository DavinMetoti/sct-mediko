// =================== MODULE QUIL ======================
class QuillEditor {
    constructor(selector, options = {}, onChangeCallback = null) {
        this.selector = selector;
        this.onChangeCallback = onChangeCallback;
        this.options = Object.assign({
            modules: {
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Please write something',
            theme: 'snow'
        }, options);

        this.init();
    }

    init() {
        const editorElement = document.querySelector(this.selector);
        if (!editorElement) {
            console.error(`Element ${this.selector} not found.`);
            return;
        }
        this.quill = new Quill(this.selector, this.options);

        this.quill.on('text-change', () => {
            if (this.onChangeCallback) {
                this.onChangeCallback(this.getContent());
            }
        });
    }

    getContent() {
        return this.quill.root.innerHTML;
    }

    setContent(content) {
        this.quill.root.innerHTML = content;
    }

    clearContent() {
        this.quill.root.innerHTML = "";
    }
}


// =================== MODULE SELECT2 ======================
class Select2Handler {
    constructor(selector, options = {}) {
        this.selector = selector;
        this.defaultOptions = {
            theme: "bootstrap-5",
            width: '100%',
            allowClear: true,
            placeholder: "Pilih opsi",
        };
        this.options = { ...this.defaultOptions, ...options };
        this.init();
    }

    init() {
        if (jQuery && $.fn.select2) {
            $(this.selector).select2(this.options);
        } else {
            console.error("Select2 tidak ditemukan. Pastikan jQuery dan Select2 telah dimuat.");
        }
    }

    updateOptions(newOptions) {
        this.options = { ...this.options, ...newOptions };
        $(this.selector).select2("destroy").select2(this.options);
    }

    getValue() {
        return $(this.selector).val();
    }

    setValue(value) {
        $(this.selector).val(value).trigger("change");
    }

    clearSelection() {
        $(this.selector).val(null).trigger("change");
    }

    destroy() {
        $(this.selector).select2("destroy");
    }
}

// =================== MODULE SELECT2 ======================
class HttpClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl.replace(/\/$/, '');
    }

    request(method, endpoint, data = {}, headers = {}) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${this.baseUrl}/${endpoint.replace(/^\//, '')}`,
                method: method,
                data: method === 'GET' ? data : JSON.stringify(data),
                contentType: 'application/json',
                dataType: 'json',
                headers: headers,
                success: (response, textStatus, xhr) => {
                    resolve({
                        status: xhr.status,
                        response: response
                    });
                },
                error: (xhr, textStatus, errorThrown) => {
                    reject({
                        status: xhr.status,
                        error: errorThrown,
                        response: xhr.responseJSON
                    });
                }
            });
        });
    }

    get(endpoint, params = {}, headers = {}) {
        return this.request('GET', endpoint, params, headers);
    }

    post(endpoint, data = {}, headers = {}) {
        return this.request('POST', endpoint, data, headers);
    }

    put(endpoint, data = {}, headers = {}) {
        return this.request('PUT', endpoint, data, headers);
    }

    delete(endpoint,data = {}, headers = {}) {
        return this.request('DELETE', endpoint, data, headers);
    }
}

