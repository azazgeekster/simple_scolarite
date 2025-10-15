import cv2
import numpy as np
import base64
from flask import Flask, request, jsonify

app = Flask(__name__)

def crop_face(image_bytes, padding_ratio=0.25):
    print("Cropping face from photo...")

    npimg = np.frombuffer(image_bytes, np.uint8)
    img = cv2.imdecode(npimg, cv2.IMREAD_COLOR)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    faces = face_cascade.detectMultiScale(gray, 1.1, 4)

    if len(faces) != 1:
        print(f"Expected 1 face, found {len(faces)}")
        return None, "Face not found or multiple faces detected"

    x, y, w, h = faces[0]
    print(f"Detected face at x={x}, y={y}, w={w}, h={h}")

    # Add padding
    pad_w = int(w * padding_ratio)
    pad_h = int(h * padding_ratio)

    x1 = max(x - pad_w, 0)
    y1 = max(y - pad_h, 0)
    x2 = min(x + w + pad_w, img.shape[1])
    y2 = min(y + h + pad_h, img.shape[0])

    cropped_face = img[y1:y2, x1:x2]
    print(f"Cropped face region: {cropped_face.shape}")

    # Encode cropped image to Base64
    _, img_encoded = cv2.imencode('.jpg', cropped_face)
    b64_encoded = base64.b64encode(img_encoded.tobytes()).decode('utf-8')

    return b64_encoded, "Face cropped and encoded successfully"

@app.route('/crop-photo', methods=['POST'])
def crop_photo():
    print("FILES:", request.files)
    print("FORM:", request.form)

    if 'avatar' not in request.files:
        return jsonify({'success': False, 'message': 'No file uploaded'}), 400

    photo = request.files['avatar']
    image_bytes = photo.read()
    print(f"Received {len(image_bytes)} bytes")
    base64_image, message = crop_face(image_bytes)

    if not base64_image:
        return jsonify({'success': False, 'message': message}), 400

    return jsonify({'success': True, 'message': message, 'cropped_base64': base64_image}), 200


if __name__ == '__main__':
    print("Starting Face Validator API on port 5001")
    app.run(port=5001, debug=True)
