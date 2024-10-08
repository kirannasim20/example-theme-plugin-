import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = ({ attributes, setAttributes }) => {
  const { title, content } = attributes;

  const [updatedTitle, setUpdatedTitle] = useState(title);
  const [updatedContent, setUpdatedContent] = useState(content);

  const onTitleChange = (newTitle) => {
    setUpdatedTitle(newTitle);
  };

  const onContentChange = (newContent) => {
    setUpdatedContent(newContent);
  };

  const onSave = () => {
    setAttributes({
      title: updatedTitle,
      content: updatedContent,
    });

    // Make AJAX request to update data on the server
    const data = {
      action: 'k_nasim_ajax', 
      title: updatedTitle,
      content: updatedContent,
    };

    jQuery.post(dataAjax.ajaxurl, data, function(response) {
      // Handle AJAX response
      console.log(response);
    });
  };

  useEffect(() => {
    // Make AJAX request to retrieve main data from the server
    const data = {
      action: 'k_nasim_get_data', 
    };

    jQuery.post(dataAjax.ajaxurl, data, function(response) {
      // Handle AJAX response
      if (response.success) {
        // Update block attributes with returned data
        setAttributes({
          title: response.data.title,
          content: response.data.content,
        });
      } else {
        // Handle error case
        console.log(response.data.message);
      }
    });
  }, []);

  return (
    <div { ...useBlockProps() }>
      <InspectorControls>
        <PanelBody title={__('Block Settings', 'data-block-domain')}>
          <TextControl
            label={__('Title', 'data-block-domain')}
            value={updatedTitle}
            onChange={onTitleChange}
          />
          <TextControl
            label={__('Content', 'data-block-domain')}
            value={updatedContent}
            onChange={onContentChange}
          />
        </PanelBody>
      </InspectorControls>

      <div className="block-content">
        <h2>{updatedTitle}</h2>
        <p>{updatedContent}</p>
        <Button isPrimary onClick={onSave}>
          {__('Save', 'data-block-domain')}
        </Button>
      </div>
    </div>
  );
};

export default Edit;
