patchelf --set-interpreter ./ld.so ./passStoreV1
patchelf --set-rpath ./ ./passStoreV1
./solve.py
