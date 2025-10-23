from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import List, Dict
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np
import uvicorn
import re

app = FastAPI(title="SBERT Skills Matcher", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global model variable
model = None

# Synonym mappings for skills
SKILL_SYNONYMS = {
    "HTML": ["html5", "html 5", "hypertext markup language", "markup"],
    "CSS": ["css3", "css 3", "stylesheets", "styling", "cascading style sheets"],
    "JavaScript": ["js", "javascript", "ecmascript", "vanilla js", "vanilla javascript"],
    "TypeScript": ["ts", "typescript"],
    "React": ["reactjs", "react.js", "react js"],
    "Vue.js": ["vue", "vuejs", "vue js"],
    "Angular": ["angularjs", "angular.js"],
    "Next.js": ["nextjs", "next js"],
    "Nuxt.js": ["nuxtjs", "nuxt js"],
    "Node.js": ["nodejs", "node js", "node"],
    "Express.js": ["express", "expressjs"],
    "Laravel": ["laravel php"],
    "Django": ["django python"],
    "Flask": ["flask python"],
    "Spring Boot": ["spring", "spring framework"],
    "ASP.NET Core": ["asp.net", "aspnet", "dotnet core", ".net core"],
    "MySQL": ["my sql"],
    "PostgreSQL": ["postgres", "psql"],
    "MongoDB": ["mongo", "mongo db"],
    "REST APIs": ["rest api", "restful", "rest", "api"],
    "GraphQL": ["graph ql", "gql"],
    "Docker": ["containerization", "containers"],
    "Kubernetes": ["k8s", "container orchestration"],
    "AWS": ["amazon web services", "amazon aws"],
    "CI/CD": ["continuous integration", "continuous deployment", "cicd"],
    "Git": ["version control", "source control"],
    "Tailwind CSS": ["tailwind", "tailwindcss"],
    "Bootstrap": ["bootstrap css"],
    "Sass/SCSS": ["sass", "scss", "syntactically awesome stylesheets"],
    "UI/UX Design": ["ui design", "ux design", "user interface", "user experience"],
    "Responsive Design": ["responsive", "mobile-first", "mobile responsive"],
    "Testing (Unit / Integration / E2E)": ["unit testing", "integration testing", "e2e testing", "end to end testing", "testing"],
    "Agile Methodology": ["agile", "agile development"],
    "Project Management": ["pm", "project manager"],
    "Machine Learning": ["ml", "artificial intelligence", "ai"],
    "Data Analysis": ["data analytics", "analytics"],
    "Problem Solving": ["troubleshooting", "debugging"]
}

# Create reverse mapping for faster lookup
SYNONYM_TO_SKILL = {}
for skill, synonyms in SKILL_SYNONYMS.items():
    for synonym in synonyms:
        SYNONYM_TO_SKILL[synonym.lower()] = skill

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
    description: str = Field(..., max_length=2000, description="User description (max 2000 characters)")
    skills: List[Skill]

class SkillSuggestion(BaseModel):
    id: int
    name: str
    similarity: float
    match_type: str = "semantic"  # "exact", "synonym", or "semantic"

class SkillResponse(BaseModel):
    success: bool
    suggested_skills: List[SkillSuggestion]
    error: str = None

def find_exact_matches(description: str, skills: List[Skill]) -> List[SkillSuggestion]:
    """Find exact keyword matches in description"""
    description_lower = description.lower()
    exact_matches = []

    for skill in skills:
        skill_name_lower = skill.name.lower()

        # Check direct skill name match
        if skill_name_lower in description_lower:
            exact_matches.append(SkillSuggestion(
                id=skill.id,
                name=skill.name,
                similarity=1.0,
                match_type="exact"
            ))
            continue

        # Check synonym matches
        if skill.name in SKILL_SYNONYMS:
            for synonym in SKILL_SYNONYMS[skill.name]:
                if synonym.lower() in description_lower:
                    exact_matches.append(SkillSuggestion(
                        id=skill.id,
                        name=skill.name,
                        similarity=0.95,
                        match_type="synonym"
                    ))
                    break

    return exact_matches

def enhance_description(description: str) -> str:
    """Enhance description by adding skill context"""
    enhanced = description

    # Add context for commonly missed skills
    patterns = {
        r'\bhtml\b': 'HTML markup language',
        r'\bcss\b': 'CSS styling',
        r'\bjs\b|\bjavascript\b': 'JavaScript programming',
        r'\bapi\b': 'REST API development',
        r'\bdatabase\b|\bdb\b': 'database management',
        r'\bfrontend\b|\bfront-end\b': 'frontend development',
        r'\bbackend\b|\bback-end\b': 'backend development',
        r'\btesting\b|\btest\b': 'software testing',
        r'\bdeployment\b|\bdeploy\b': 'deployment and DevOps'
    }

    for pattern, context in patterns.items():
        if re.search(pattern, description, re.IGNORECASE):
            enhanced += f" {context}"

    return enhanced

@app.post("/generate-skills", response_model=SkillResponse)
async def generate_skills(request: SkillRequest):
    try:
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

        print(f"🔍 Processing description: {request.description[:200]}...")

        # Step 1: Find exact/synonym matches
        exact_matches = find_exact_matches(request.description, request.skills)
        exact_skill_ids = {match.id for match in exact_matches}

        print(f"🎯 Found {len(exact_matches)} exact/synonym matches")

        # Step 2: Enhance description for better semantic matching
        enhanced_description = enhance_description(request.description)

        # Step 3: Semantic matching for remaining skills
        remaining_skills = [skill for skill in request.skills if skill.id not in exact_skill_ids]
        semantic_matches = []

        if remaining_skills:
            description_embedding = model.encode([enhanced_description])
            skill_names = [skill.name for skill in remaining_skills]
            skill_embeddings = model.encode(skill_names)

            similarities = cosine_similarity(description_embedding, skill_embeddings)[0]

            # Debug: print every similarity so you can inspect why a skill was excluded
            for i, skill in enumerate(remaining_skills):
                similarity_score = float(similarities[i])
                print(f"DEBUG similarity -> '{skill.name}': {similarity_score:.3f}")

            # Primary semantic picks (keep your existing threshold)
            for i, skill in enumerate(remaining_skills):
                similarity_score = float(similarities[i])
                if similarity_score > 0.40:
                    semantic_matches.append(SkillSuggestion(
                        id=skill.id,
                        name=skill.name,
                        similarity=similarity_score,
                        match_type="semantic"
                    ))

            # Fallback: if too few semantic matches, add top candidates down to a min score
            if len(semantic_matches) < 12:
                min_fallback_score = 0.20
                sorted_idx = sorted(range(len(similarities)), key=lambda k: similarities[k], reverse=True)
                added_ids = {m.id for m in semantic_matches}
                for idx in sorted_idx:
                    if len(semantic_matches) >= 12:
                        break
                    if remaining_skills[idx].id in added_ids:
                        continue
                    score = float(similarities[idx])
                    if score >= min_fallback_score:
                        semantic_matches.append(SkillSuggestion(
                            id=remaining_skills[idx].id,
                            name=remaining_skills[idx].name,
                            similarity=score,
                            match_type="semantic"
                        ))
                    else:
                        break

        # Combine and sort results
        all_matches = exact_matches + semantic_matches
        all_matches.sort(key=lambda x: x.similarity, reverse=True)

        # Return top 12 results
        top_results = all_matches[:12]

        print(f"✨ Total relevant skills found: {len(top_results)}")
        for i, skill in enumerate(top_results[:5]):
            print(f"   {i+1}. {skill.name}: {skill.similarity:.3f} ({skill.match_type})")

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
