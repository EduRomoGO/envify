class Image < ApplicationRecord
  def url
    "#{Rails.public_path}#{path}"
  end
end
