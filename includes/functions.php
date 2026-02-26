<?php
require_once __DIR__.'/config.php';

if(session_status() === PHP_SESSION_NONE) session_start();

// CSRF helpers
function csrf_token(){
    if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf_token'];
}
function verify_csrf($token){
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// LEVELS
function addLevel($name){
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO levels (name) VALUES (?)');
    return $stmt->execute([$name]);
}

function getLevels(){ global $pdo; return $pdo->query('SELECT * FROM levels ORDER BY id')->fetchAll(PDO::FETCH_ASSOC); }

function levelHasClasses($level_id){ global $pdo; $stmt = $pdo->prepare('SELECT COUNT(*) FROM classes WHERE level_id=?'); $stmt->execute([$level_id]); return $stmt->fetchColumn()>0; }

// CLASSES
function addClass($level_id,$name,$code){ global $pdo; $stmt = $pdo->prepare('INSERT INTO classes (level_id,name,code) VALUES (?,?,?)'); return $stmt->execute([$level_id,$name,$code]); }

function getClassesByLevel(){ global $pdo; $stmt = $pdo->query('SELECT c.*, l.name as level_name FROM classes c JOIN levels l ON c.level_id=l.id ORDER BY l.id'); return $stmt->fetchAll(PDO::FETCH_ASSOC); }

function getClassById($id){ global $pdo; $stmt=$pdo->prepare('SELECT * FROM classes WHERE id=?'); $stmt->execute([$id]); return $stmt->fetch(PDO::FETCH_ASSOC); }

function getClassWithLevel($id){
    global $pdo;
    $stmt = $pdo->prepare('SELECT c.*, l.name as level_name FROM classes c JOIN levels l ON c.level_id=l.id WHERE c.id=?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// MODULES
function addModule($code,$name){
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO modules (code,name) VALUES (?,?)');
    if ($stmt->execute([$code,$name])) {
        return (int)$pdo->lastInsertId();
    }
    return false;
}

function assignModuleToClass($class_id,$module_id){ global $pdo; $stmt=$pdo->prepare('INSERT INTO class_modules (class_id,module_id) VALUES (?,?)'); return $stmt->execute([$class_id,$module_id]); }

function getModulesByClass($class_id){ global $pdo; $stmt=$pdo->prepare('SELECT m.* FROM modules m JOIN class_modules cm ON cm.module_id=m.id WHERE cm.class_id=?'); $stmt->execute([$class_id]); return $stmt->fetchAll(PDO::FETCH_ASSOC); }

function getModuleByCode($code){ global $pdo; $stmt=$pdo->prepare('SELECT * FROM modules WHERE code=?'); $stmt->execute([$code]); return $stmt->fetch(PDO::FETCH_ASSOC); }

// STUDENTS
function addStudent($matricule,$firstname,$lastname,$class_id){ global $pdo; $stmt=$pdo->prepare('INSERT INTO students (matricule,firstname,lastname,class_id) VALUES (?,?,?,?)'); return $stmt->execute([$matricule,$firstname,$lastname,$class_id]); }

function getStudentsByClass($class_id){ global $pdo; $stmt=$pdo->prepare('SELECT * FROM students WHERE class_id=?'); $stmt->execute([$class_id]); return $stmt->fetchAll(PDO::FETCH_ASSOC); }

function getStudentByMatricule($matricule){ global $pdo; $stmt=$pdo->prepare('SELECT * FROM students WHERE matricule=?'); $stmt->execute([$matricule]); return $stmt->fetch(PDO::FETCH_ASSOC); }

    global $pdo;
    $class = getClassWithLevel($class_id);
    if (!$class) return null;

    $level_code = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $class['level_name']));
    $class_code = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $class['name']));
    $prefix = $level_code.$class_code.'-';

    $stmt = $pdo->prepare('SELECT code FROM modules WHERE code LIKE ?');
    $stmt->execute([$prefix.'%']);
    $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $max = 0;
    foreach ($codes as $code) {
        $parts = explode('-', $code);
        $last = end($parts);
        if (ctype_digit($last)) {
            $num = (int)$last;
            if ($num > $max) $max = $num;
        }
    }

    $next = $max + 1;
    return $prefix.str_pad((string)$next, 2, '0', STR_PAD_LEFT);

// EVALUATIONS
function addEvaluation($student_id,$module_id,$type,$score,$date=null){ global $pdo; $stmt=$pdo->prepare('INSERT INTO evaluations (student_id,module_id,type,score,date_eval) VALUES (?,?,?,?,?)'); return $stmt->execute([$student_id,$module_id,$type,$score,$date ?: date('Y-m-d')]); }

function updateEvaluationByMatriculeModule($matricule,$module_code,$type,$score){ global $pdo; $stmt=$pdo->prepare('UPDATE evaluations e JOIN students s ON e.student_id=s.id JOIN modules m ON e.module_id=m.id SET e.type=?, e.score=? WHERE s.matricule=? AND m.code=?'); return $stmt->execute([$type,$score,$matricule,$module_code]); }

function deleteEvaluationByMatriculeModule($matricule,$module_code){ global $pdo; $stmt=$pdo->prepare('DELETE e FROM evaluations e JOIN students s ON e.student_id=s.id JOIN modules m ON e.module_id=m.id WHERE s.matricule=? AND m.code=?'); return $stmt->execute([$matricule,$module_code]); }

function getEvaluationsByStudent($student_id){ global $pdo; $stmt=$pdo->prepare('SELECT e.*, m.code as module_code, m.name as module_name FROM evaluations e JOIN modules m ON e.module_id=m.id WHERE e.student_id=?'); $stmt->execute([$student_id]); return $stmt->fetchAll(PDO::FETCH_ASSOC); }

// CALCULS
function calculateStudentAverage($student_id){ global $pdo; $stmt=$pdo->prepare("SELECT AVG(score) FROM evaluations WHERE student_id=? AND type IN ('DEVOIR','EXAM')"); $stmt->execute([$student_id]); $avg=$stmt->fetchColumn(); return $avg!==false ? (float)$avg : null; }

function calculateClassAverage($class_id){ global $pdo; $stmt=$pdo->prepare('SELECT AVG(e.score) FROM evaluations e JOIN students s ON e.student_id=s.id WHERE s.class_id=? AND e.type IN (\'DEVOIR\',\'EXAM\')'); $stmt->execute([$class_id]); $avg=$stmt->fetchColumn(); return $avg!==false ? (float)$avg : null; }

function bestStudentInClass($class_id){ global $pdo; $stmt=$pdo->prepare('SELECT s.*, AVG(e.score) as avg_score FROM students s JOIN evaluations e ON e.student_id=s.id WHERE s.class_id=? AND e.type IN (\'DEVOIR\',\'EXAM\') GROUP BY s.id ORDER BY avg_score DESC LIMIT 1'); $stmt->execute([$class_id]); return $stmt->fetch(PDO::FETCH_ASSOC); }

function studentsAboveClassAverage($class_id){ global $pdo; $classAvg = calculateClassAverage($class_id); if($classAvg===null) return []; $stmt=$pdo->prepare('SELECT s.*, AVG(e.score) as avg_score FROM students s JOIN evaluations e ON e.student_id=s.id WHERE s.class_id=? AND e.type IN (\'DEVOIR\',\'EXAM\') GROUP BY s.id HAVING avg_score > ?'); $stmt->execute([$class_id,$classAvg]); return $stmt->fetchAll(PDO::FETCH_ASSOC); }

// DASHBOARD / STATS
function stats(){
    global $pdo;
    $r = [];
    $r['nb_levels'] = $pdo->query('SELECT COUNT(*) FROM levels')->fetchColumn();
    $r['nb_classes'] = $pdo->query('SELECT COUNT(*) FROM classes')->fetchColumn();
    $r['nb_students'] = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    return $r;
}

function studentStatus($student_id){ $avg = calculateStudentAverage($student_id); if($avg===null) return 'N/A'; if($avg>=10) return 'Admis'; if($avg>=5) return 'Ajourné'; return 'Exclus'; }

// UPDATES / DELETES
function updateClass($id,$level_id,$name,$code){ global $pdo; $stmt=$pdo->prepare('UPDATE classes SET level_id=?, name=?, code=? WHERE id=?'); return $stmt->execute([$level_id,$name,$code,$id]); }
function deleteClass($id){ global $pdo; $stmt=$pdo->prepare('DELETE FROM classes WHERE id=?'); return $stmt->execute([$id]); }

function getModuleById($id){ global $pdo; $stmt=$pdo->prepare('SELECT * FROM modules WHERE id=?'); $stmt->execute([$id]); return $stmt->fetch(PDO::FETCH_ASSOC); }
function updateModule($id,$code,$name){ global $pdo; $stmt=$pdo->prepare('UPDATE modules SET code=?, name=? WHERE id=?'); return $stmt->execute([$code,$name,$id]); }
function deleteModule($id){ global $pdo; $stmt=$pdo->prepare('DELETE FROM modules WHERE id=?'); return $stmt->execute([$id]); }

function getStudentById($id){ global $pdo; $stmt=$pdo->prepare('SELECT * FROM students WHERE id=?'); $stmt->execute([$id]); return $stmt->fetch(PDO::FETCH_ASSOC); }
function updateStudent($id,$matricule,$firstname,$lastname,$class_id){ global $pdo; $stmt=$pdo->prepare('UPDATE students SET matricule=?, firstname=?, lastname=?, class_id=? WHERE id=?'); return $stmt->execute([$matricule,$firstname,$lastname,$class_id,$id]); }
function deleteStudent($id){ global $pdo; $stmt=$pdo->prepare('DELETE FROM students WHERE id=?'); return $stmt->execute([$id]); }
