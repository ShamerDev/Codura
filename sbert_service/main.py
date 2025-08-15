from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import List, Dict
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np
import uvicorn

app = FastAPI(title="SBERT Skills Matcher", version="1.0.0")

# Add CORS middleware to allow Laravel to call this service
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # In production, specify your Laravel domain
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global model variable
model = None

@app.on_event("startup")
async def startup_event():
    global model
    print("🚀 Loading SBERT model (all-MiniLM-L6-v2)...")
    try:
        model = SentenceTransformer('all-MiniLM-L6-v2')
        print("✅ Model loaded successfully!")
    except Exception as e:
        print(f"❌ Failed to load model: {e}")

class Skill(BaseModel):
    id: int
    name: str

class SkillRequest(BaseModel):
    description: str
    skills: List[Skill]

class SkillSuggestion(BaseModel):
    id: int
    name: str
    similarity: float

class SkillResponse(BaseModel):
    success: bool
    suggested_skills: List[SkillSuggestion]
    error: str = None

@app.post("/generate-skills", response_model=SkillResponse)
async def generate_skills(request: SkillRequest):
    try:
        # Validation
        if not request.description.strip():
            return SkillResponse(
                success=False,
                suggested_skills=[],
                error="Description cannot be empty"
            )

        if not request.skills:
            return SkillResponse(
                success=False,
                suggested_skills=[],
                error="No skills provided"
            )

        if model is None:
            return SkillResponse(
                success=False,
                suggested_skills=[],
                error="SBERT model is not loaded"
            )

        print(f"🔍 Processing description: {request.description[:100]}...")
        print(f"📊 Comparing against {len(request.skills)} skills")

        # Encode description
        description_embedding = model.encode([request.description])

        # Extract skill names and encode them
        skill_names = [skill.name for skill in request.skills]
        skill_embeddings = model.encode(skill_names)

        # Calculate cosine similarities
        similarities = cosine_similarity(description_embedding, skill_embeddings)[0]

        # Create results with similarity scores
        results = []
        for i, skill in enumerate(request.skills):
            similarity_score = float(similarities[i])
            # Only include skills with similarity above threshold
            if similarity_score > 0.15:
                results.append(SkillSuggestion(
                    id=skill.id,
                    name=skill.name,
                    similarity=similarity_score
                ))

        # Sort by similarity score (highest first)
        results.sort(key=lambda x: x.similarity, reverse=True)

        # Return top 10 most similar skills
        top_results = results[:10]

        print(f"✨ Found {len(top_results)} relevant skills")
        for skill in top_results[:3]:  # Log top 3
            print(f"   - {skill.name}: {skill.similarity:.3f}")

        return SkillResponse(
            success=True,
            suggested_skills=top_results
        )

    except Exception as e:
        print(f"❌ Error processing request: {e}")
        return SkillResponse(
            success=False,
            suggested_skills=[],
            error=f"Processing error: {str(e)}"
        )

@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "model_loaded": model is not None,
        "service": "SBERT Skills Matcher"
    }

@app.get("/")
async def root():
    return {
        "message": "SBERT Skills Matcher API is running!",
        "docs": "/docs",
        "health": "/health"
    }

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8001, log_level="info")
